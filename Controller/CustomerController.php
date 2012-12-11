<?php

namespace TerraMar\Bundle\SalesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use TerraMar\Bundle\SalesBundle\Factory\CustomerUser\DuplicateEmailException;
use Symfony\Component\Form\FormError;
use TerraMar\Bundle\CustomerBundle\Controller\Customer\SearchController;
use TerraMar\Bundle\CustomerBundle\Form\CustomerType;
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
class CustomerController extends AbstractController
{
    /**
     * Lists all Customer entities.
     *
     * @Route("s/", name="customers")
     * @Template()
     * @Secure(roles="ROLE_CUSTOMER_READ")
     */
    public function indexAction(Request $request)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('TerraMarCustomerBundle:Customer')->findAllByOffice($this->getCurrentOffice(), 50);

        $this->get('terramar.customer.helper.search_results')->setLastSearchResults(SearchController::LAST_SEARCH_KEY, $entities);

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Lists all recently added Customer entities.
     *
     * @Route("s/recent", name="customers_recent")
     * @Template("TerraMarSalesBundle:Customer:index.html.twig")
     * @Secure(roles="ROLE_CUSTOMER_READ")
     */
    public function recentAction()
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $entities = $em->createQueryBuilder()
            ->select('c')
            ->from('TerraMarCustomerBundle:Customer', 'c')
            ->where('c.active = true')
            ->orderBy('c.dateCreated', 'DESC')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult();

        $this->get('terramar.customer.helper.search_results')->setLastSearchResults(SearchController::LAST_SEARCH_KEY, $entities);

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Shows a Customer
     *
     * @Route("/{id}/show", name="customer_show")
     * @Template
     * @Secure(roles="ROLE_CUSTOMER_WRITE")
     */
    public function showAction($id)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $customer = $em->getRepository('TerraMarCustomerBundle:Customer')->find($id);

        if (!$customer) {
            throw $this->createNotFoundException('Unable to locate Customer');
        }

        return array(
            'entity' => $customer
        );
    }

    /**
     * Shows a form to create a new Customer
     *
     * @Route("/new", name="customer_new")
     * @Template
     * @Secure(roles="ROLE_CUSTOMER_WRITE")
     */
    public function newAction()
    {
        $form = $this->createForm(new CustomerType());

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * Shows a form to create a new Customer
     *
     * @Route("/create", name="customer_create")
     * @Template("TerraMarSalesBundle:Customer:new.html.twig")
     * @Method("POST")
     * @Secure(roles="ROLE_CUSTOMER_WRITE")
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(new CustomerType());
        $form->bind($request);

        if ($form->isValid()) {
            $entity = $form->getData();

            try {
                /** @var $factory \TerraMar\Bundle\CustomerBundle\Factory\CustomerFactoryInterface */
                $factory = $this->get('terramar.customer.factory.customer');
                $factory->buildCustomer($entity);

                $profile = $this->get('terramar.sales.factory.customer_sales_profile')->create($entity, $this->getCurrentOffice());

                $em = $this->getDoctrine()->getManager();
                $em->persist($profile);
                $em->persist($entity);
                $em->flush();

                $this->getSession()->getFlashBag()->set('success', 'The customer has been created successfully.');

                return $this->redirect($this->generateUrl('customers'));
            } catch (DuplicateEmailException $e) {
                $form->get('emailAddress')->addError(new FormError('This email address is in use by another customer.'));
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * Deletes a Customer
     *
     * @Route("/{id}/delete", name="customer_delete")
     * @Method("POST")
     * @Secure(roles="ROLE_CUSTOMER_WRITE")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TerraMar\Bundle\CustomerBundle\Entity\Customer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to locate Customer');
        }

        $entity->setActive(false);
        $em->persist($entity);
        $em->flush();

        $this->getSession()->getFlashBag()->set('success', 'The customer has been deleted successfully.');

        return $this->redirect($this->generateUrl('customers'));
    }

    /**
     * Shows a form to edit an existing Customer
     *
     * @Route("/{id}/edit", name="customer_edit")
     * @Template
     * @Secure(roles="ROLE_CUSTOMER_WRITE")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TerraMar\Bundle\CustomerBundle\Entity\Customer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to locate Customer');
        }

        $form = $this->createForm(new CustomerType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }

    /**
     * Updates an existing Customer
     *
     * @Route("/{id}/update", name="customer_update")
     * @Template("TerraMarSalesBundle:Customer:edit.html.twig")
     * @Method("POST")
     * @Secure(roles="ROLE_CUSTOMER_WRITE")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TerraMar\Bundle\CustomerBundle\Entity\Customer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to locate Customer');
        }

        $form = $this->createForm(new CustomerType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            try {
                $em->persist($entity);
                $em->flush();

                $this->getSession()->getFlashBag()->set('success', 'The customer has been updated successfully.');

                return $this->redirect($this->generateUrl('customers'));
            } catch (DuplicateEmailException $e) {
                $form->get('emailAddress')->addError(new FormError('This email address is in use by another customer.'));
            }
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }

    /**
     * De-Activate a Customer.
     *
     * @Route("/{id}/deactivate", name="customer_deactivate")
     * @Secure(roles="ROLE_CUSTOMER_WRITE")
     */
    public function deactivateAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $customer = $em->getRepository('TerraMarCustomerBundle:Customer')->find($id);

        if (!$customer) {
            throw $this->createNotFoundException('Unable to find Customer entity.');
        }

        $helper = $this->get('terramar.customer.helper.customer');
        $helper->deactivateCustomer($customer);
        $em->persist($customer);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'Customer deactivated successfully.');

        return new Response(json_encode(array('type' => 'success', 'reload' => true)));
    }

    /**
     * Activate a Customer.
     *
     * @Route("/{id}/activate", name="customer_activate")
     * @Secure(roles="ROLE_CUSTOMER_WRITE")
     */
    public function activateAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $customer = $em->getRepository('TerraMarCustomerBundle:Customer')->find($id);

        if (!$customer) {
            throw $this->createNotFoundException('Unable to find Customer entity.');
        }

        $helper = $this->get('terramar.customer.helper.customer');
        $helper->activateCustomer($customer);
        $em->persist($customer);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'Customer activated successfully.');

        return new Response(json_encode(array('type' => 'success', 'reload' => true)));
    }
}
