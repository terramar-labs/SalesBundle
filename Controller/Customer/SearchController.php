<?php

namespace TerraMar\Bundle\SalesBundle\Controller\Customer;

use Symfony\Component\HttpFoundation\Request;
use Orkestra\Bundle\ApplicationBundle\Controller\Controller;
use TerraMar\Bundle\SalesBundle\Controller\AbstractController;
use TerraMar\Bundle\CustomerBundle\Form\AdvancedSearchType;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use TerraMar\Bundle\CustomerBundle\Entity\Customer;

/**
 * Customer controller.
 *
 * @Route("/customer")
 */
class SearchController extends Controller
{
    const LAST_SEARCH_KEY = '__customer_last_search';

    /**
     * Display's the advanced search form.
     *
     * @Route("s/advanced-search", name="customer_search_advanced")
     * @Template()
     * @Method("GET")
     * @Secure(roles="ROLE_CUSTOMER_READ")
     */
    public function advancedSearchAction()
    {
        $form = $this->createForm(new AdvancedSearchType());

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * Lists all Customer entities from an Advanced Search.
     *
     * @Route("s/advanced-search")
     * @Method("POST")
     * @Template()
     * @Secure(roles="ROLE_CUSTOMER_READ")
     */
    public function doAdvancedSearchAction(Request $request)
    {
        $form = $this->createForm(new AdvancedSearchType());

        $form->bind($request);

        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        if ($form->isValid()) {
            $wrap = function($str) { return '%' . $str . '%'; };

            $data = $form->getData();

            $qb = $em->createQueryBuilder()
                ->select('c')
                ->from('TerraMarCustomerBundle:Customer', 'c')
                ->join('c.contactAddress', 'ca');

            if ($data['firstName']) {
                $qb->andWhere('c.firstName LIKE :firstName')
                    ->setParameter('firstName', $wrap($data['firstName']));
            }

            if ($data['lastName']) {
                $qb->andWhere('c.lastName LIKE :lastName')
                    ->setParameter('lastName', $wrap($data['lastName']));
            }

            if ($data['phone']) {
                $qb->andWhere('ca.phone LIKE :phone')
                    ->setParameter('phone', $wrap($data['phone']));
            }

            if ($data['emailAddress']) {
                $qb->andWhere('c.emailAddress LIKE :emailAddress')
                    ->setParameter('emailAddress', $wrap($data['emailAddress']));
            }

            if ($data['streetAddress']) {
                $qb->andWhere('ca.street LIKE :streetAddress')
                    ->setParameter('streetAddress', $wrap($data['streetAddress']));
            }

            if ($data['city']) {
                $qb->andWhere('ca.city LIKE :city')
                    ->setParameter('city', $wrap($data['city']));
            }

            if ($data['state']) {
                $qb->andWhere('ca.region = :state')
                    ->setParameter('state', $data['state']);
            }

            if ($data['zip']) {
                $qb->andWhere('ca.postalCode LIKE :zip')
                    ->setParameter('zip', $wrap($data['zip']));
            }

            if ($data['customerStatus']) {
                $qb->andWhere('c.status LIKE :customerStatus')
                    ->setParameter('customerStatus', $wrap($data['customerStatus']));
            }

            if ($data['lastModified']) {
                $qb->andWhere('c.modifiedBy = :lastModified')
                    ->setParameter('lastModified', $data['lastModified']);
            }

            $entities = $qb->getQuery()
                ->setMaxResults(50)
                ->getResult();
        } else {
            $entities = array();
        }

        $this->get('terramar.customer.helper.search_results')->setLastSearchResults(self::LAST_SEARCH_KEY, $entities);

        return array(
            'entities' => $entities,
        );
    }

    /**
     * @Route("s/search.{_format}", name="customer_search_suggestions", defaults={"_format"="html"})
     * @Template
     * @Secure(roles="ROLE_CUSTOMER_READ")
     */
    public function suggestionsAction(Request $request)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $query = $request->get('query');

        $qb = $em->createQueryBuilder();

        $entities = $qb->select('c')
            ->from('TerraMarCustomerBundle:Customer', 'c')
            ->where($qb->expr()->orX(
                $qb->expr()->like('c.firstName', ':searchTerm'),
                $qb->expr()->like('c.lastName', ':searchTerm'),
                $qb->expr()->like('c.emailAddress', ':searchTerm')
            ))
            ->setParameter('searchTerm', '%' . $query . '%')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult();

        if (!$request->isXmlHttpRequest()) {
            $this->get('terramar.customer.helper.search_results')->setLastSearchResults(self::LAST_SEARCH_KEY, $entities);
        }

        return array(
            'query' => $query,
            'entities' => $entities,
        );
    }

    /**
     * @Route("/export/download", name="customer_export_download")
     * @Secure(roles="ROLE_CUSTOMER_READ")
     */
    public function downloadAction()
    {
        $export = $this->exportLastSearchResults();

        return new Response($export, 200, array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="search_results.csv"'
        ));
    }

    /**
     * @Route("/export/email", name="customer_export_email")
     * @Template
     * @Secure(roles="ROLE_CUSTOMER_READ")
     */
    public function emailAction(Request $request)
    {
        $form = $this->createFormBuilder(array('body' => 'Attached is an export of search results.'))
            ->add('from')
            ->add('recipient')
            ->add('subject')
            ->add('body', 'textarea')
            ->getForm();

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                $export = $this->exportLastSearchResults();

                $data = $form->getData();

                $message = \Swift_Message::newInstance()
                    ->setSubject($data['subject'])
                    ->setFrom($data['from'])
                    ->setReplyTo($data['from'])
                    ->setTo($data['recipient'])
                    ->setBody($data['body'])
                    ->attach(\Swift_Attachment::newInstance($export, 'search_results.csv', 'text/csv'));

                $this->get('mailer')->send($message);

                $this->getSession()->getFlashBag()->set('success', 'The search results have been emailed successfully.');

                return $this->redirect($this->generateUrl('customers'));
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    private function exportLastSearchResults()
    {
        $results = $this->get('terramar.customer.helper.search_results')->getLastSearchResults(self::LAST_SEARCH_KEY);

        if (!empty($results)) {
            /** @var \Doctrine\ORM\EntityManager $em */
            $em = $this->getDoctrine()->getManager();

            $entities = $em->createQueryBuilder()
                ->select('c')
                ->from('TerraMarCustomerBundle:Customer', 'c')
                ->join('c.contactAddress', 'ca')
                ->andWhere('c.id IN (:results)')
                ->setParameter('results', $results)
                ->getQuery()
                ->getResult();
        } else {
            $entities = array();
        }

        $helper = $this->get('terramar.customer.helper.export');

        return $helper->exportToCsv($entities, array(
            'First Name' => 'firstName',
            'Last Name' => 'lastName',
            'Phone' => 'contactAddress.phone',
            'Email' => 'emailAddress',
            'Zip Code' => 'contactAddress.postalCode',
            'Status' => 'status.value',
            'Last Modified' => 'dateModified'
        ));
    }
}
