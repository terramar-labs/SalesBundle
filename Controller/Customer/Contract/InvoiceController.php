<?php

namespace TerraMar\Bundle\SalesBundle\Controller\Customer\Contract;

use Symfony\Component\HttpFoundation\Request;
use TerraMar\Bundle\SalesBundle\Form\NewInvoiceType;
use Symfony\Component\Form\FormError;
use Orkestra\Transactor\Entity\Result\ResultStatus;
use Symfony\Component\HttpFoundation\Response;
use TerraMar\Bundle\SalesBundle\Form\InvoiceType;
use TerraMar\Bundle\SalesBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use TerraMar\Bundle\SalesBundle\Entity\Invoice;

/**
 * Invoice controller.
 *
 * @Route("/customer")
 */
class InvoiceController extends AbstractController
{
    /**
     * @Route("/{id}/contract/{contractid}/invoices", name="customer_contract_invoices")
     * @Template
     * @Secure(roles="ROLE_INVOICE_READ")
     */
    public function indexAction($id, $contractid)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $customer = $em->getRepository('TerraMarCustomerBundle:Customer')->findOneByIdAndOffice($id, $this->getCurrentOffice());

        if (!$customer) {
            throw $this->createNotFoundException('Unable to find Customer entity.');
        }

        $contract = $em->getRepository('TerraMarSalesBundle:Contract')->findOneByIdAndCustomer($contractid, $customer);

        if (!$contract) {
            throw $this->createNotFoundException('Unable to locate Contract');
        }

        return array(
            'entity' => $customer,
            'contract' => $contract,
            'entities' => $contract->getInvoices()
        );
    }

    /**
     * @Route("/{id}/contract/{contractid}/invoice/new", name="customer_contract_invoice_new")
     * @Template
     * @Secure(roles="ROLE_INVOICE_WRITE")
     */
    public function newAction($id, $contractid)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $customer = $em->getRepository('TerraMarCustomerBundle:Customer')->findOneByIdAndOffice($id, $this->getCurrentOffice());

        if (!$customer) {
            throw $this->createNotFoundException('Unable to find Customer entity.');
        }

        $contract = $em->getRepository('TerraMarSalesBundle:Contract')->findOneByIdAndCustomer($contractid, $customer);

        if (!$contract) {
            throw $this->createNotFoundException('Unable to locate Contract');
        }

        $form = $this->createForm(new NewInvoiceType());

        return array(
            'entity' => $customer,
            'contract' => $contract,
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/{id}/contract/{contractid}/invoice/create", name="customer_contract_invoice_create")
     * @Template("TerraMarSalesBundle:Customer/Contract/Invoice:new.html.twig")
     * @Method("POST")
     * @Secure(roles="ROLE_INVOICE_WRITE")
     */
    public function createAction(Request $request, $id, $contractid)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $customer = $em->getRepository('TerraMarCustomerBundle:Customer')->findOneByIdAndOffice($id, $this->getCurrentOffice());

        if (!$customer) {
            throw $this->createNotFoundException('Unable to find Customer entity.');
        }

        $contract = $em->getRepository('TerraMarSalesBundle:Contract')->findOneByIdAndCustomer($contractid, $customer);

        if (!$contract) {
            throw $this->createNotFoundException('Unable to locate Contract');
        }

        $form = $this->createForm(new NewInvoiceType());
        $form->bind($request);

        if ($form->isValid()) {
            $entity = $form->getData();

            $factory = $this->get('terramar.sales.factory.invoice');
            $factory->buildInvoice($entity);

            $contract->addInvoice($entity);

            $em->persist($contract);
            $em->flush();

            $this->getSession()->getFlashBag()->set('success', 'Invoice created successfully.');

            return $this->redirect($this->generateUrl('customer_contract_invoices', array('id' => $id, 'contractid' => $contractid)));
        }

        return array(
            'entity' => $customer,
            'contract' => $contract,
            'form' => $form->createView()
        );
    }
}
