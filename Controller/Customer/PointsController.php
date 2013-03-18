<?php

namespace Terramar\Bundle\SalesBundle\Controller\Customer;

use Symfony\Component\HttpFoundation\Request;
use Terramar\Bundle\SalesBundle\Form\AddCreditType;
use Terramar\Bundle\SalesBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Customer payment account controller
 *
 * @Route("/customer")
 */
class PointsController extends AbstractController
{
    /**
     * Displays a form to add credit to a Customer's PointsAccount
     *
     * @Route("/{id}/credit/add", name="customer_credit_new")
     * @Template()
     * @Secure(roles="ROLE_CUSTOMER_WRITE")
     */
    public function newAction($id)
    {
        $this->requiresCurrentOffice();
        $office = $this->getCurrentOffice();

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TerramarCustomerBundle:Customer')->findOneByIdAndOffice($id, $office);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Customer entity.');
        }

        $profile = $em->getRepository('TerramarSalesBundle:CustomerSalesProfile')->findOneByCustomer($entity);

        if (!$profile) {
            throw $this->createNotFoundException('Unable to find Customer Sales Profile entity.');
        }

        if (!$profile->getPointsAccount()) {
            throw $this->createNotFoundException('Unable to find Points Account entity.');
        }

        $form = $this->createForm(new AddCreditType());

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Edits an existing Account
     *
     * @Route("/{id}/credit/create", name="customer_credit_create", defaults={"_format"="json"})
     * @Method("POST")
     * @Template()
     * @Secure(roles="ROLE_CUSTOMER_WRITE")
     */
    public function createAction(Request $request, $id)
    {
        $this->requiresCurrentOffice();
        $office = $this->getCurrentOffice();

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TerramarCustomerBundle:Customer')->findOneByIdAndOffice($id, $office);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Customer entity.');
        }

        $profile = $em->getRepository('TerramarSalesBundle:CustomerSalesProfile')->findOneByCustomer($entity);

        if (!$profile) {
            throw $this->createNotFoundException('Unable to find Customer Sales Profile entity.');
        }

        if (!($account = $profile->getPointsAccount())) {
            throw $this->createNotFoundException('Unable to find Points Account entity.');
        }

        $form = $this->createForm(new AddCreditType());
        $form->bind($request);

        if ($form->isValid()) {
            $amount = $form->get('amount')->getData();

            $helper = $this->get('terramar.sales.helper.account');
            $result = $helper->addCredit($profile, $amount);

            $em->persist($result);
            $em->persist($account);
            $em->persist($profile);
            $em->flush();

            $this->getSession()->getFlashBag()->add('success', 'Credit successfully issued.');

            return new Response(json_encode(array('type' => 'success', 'reload' => true)));
        } else {
            return new Response(json_encode(array('type' => 'error', 'message' => 'An error occurred while applying the credit.')));
        }
    }
}
