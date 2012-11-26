<?php

namespace TerraMar\Bundle\SalesBundle\Controller\Customer;

use Symfony\Component\HttpFoundation\Request;
use TerraMar\Bundle\SalesBundle\Http\JsonReloadResponse;
use TerraMar\Bundle\SalesBundle\Http\JsonErrorResponse;
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
 * @Route("")
 */
class InvoiceController extends AbstractController
{
    const INVOICE_LAST_PROCESSED_KEY = '__invoice_last_processed';
    const INVOICE_LAST_PAYMENT_DATA_KEY = '__invoice_last_payment_data';

    /**
     * Lists all Invoice entities.
     *
     * @Route("customer/{id}/invoices/", name="customer_invoices")
     * @Template()
     * @Secure(roles="ROLE_CUSTOMER_READ")
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('TerraMarSalesBundle:Invoice')->findBy(array('active' => true));

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Shows an Invoice entity
     *
     * @Route("customer/{id}/invoice/{invoiceid}/show", name="customer_invoice_show")
     * @Template()
     * @Secure(roles="ROLE_CUSTOMER_WRITE")
     */
    public function showAction($id, $invoiceid)
    {
        $em = $this->getDoctrine()->getManager();

        $customer = $em->getRepository('TerraMarCustomerBundle:Customer')->find($id);

        if (!$customer) {
            throw $this->createNotFoundException('Unable to find Customer entity.');
        }

        $profile = $em->getRepository('TerraMarSalesBundle:CustomerSalesProfile')->findOneByCustomer($customer);

        $invoice = $em->getRepository('TerraMarSalesBundle:Invoice')->findOneBy(array('id' => $invoiceid));

        $form = $this->createForm(new InvoiceType($profile), $invoice);

        $session = $this->getSession();
        if ($session->has(self::INVOICE_LAST_PROCESSED_KEY)
            && $session->has(self::INVOICE_LAST_PAYMENT_DATA_KEY)
            && $invoiceid == $session->get(self::INVOICE_LAST_PROCESSED_KEY)
        ) {
            /** @var \TerraMar\Bundle\SalesBundle\Helper\InvoiceHelper $helper */
            $helper = $this->get('terramar.sales.helper.invoice');

            $payments = $errors = array();

            foreach ($session->get(self::INVOICE_LAST_PAYMENT_DATA_KEY) as $index => $error) {
                $payments[$index] = $helper->hydrateSerializedPayment($error['payment']);
                $errors[$index] = $error['message'];
            }

            $paymentsForm = $form->get('payments');
            $paymentsForm->setData($payments);

            foreach ($errors as $index => $message) {
                $paymentsForm->get($index)->addError(new FormError($message));
            }

            $session->remove(self::INVOICE_LAST_PROCESSED_KEY);
            $session->remove(self::INVOICE_LAST_PAYMENT_DATA_KEY);
        }

        return array(
            'form' => $form->createView(),
            'entity' => $customer,
            'profile' => $profile,
            'invoice' => $invoice,
        );
    }

    /**
     * Shows an Invoice entity
     *
     * @Route("customer/{id}/invoice/{invoiceid}/process", name="customer_invoice_process", defaults={"_format"="json"})
     * @Template()
     * @Secure(roles="ROLE_CUSTOMER_WRITE")
     */
    public function updateAction(Request $request, $id, $invoiceid)
    {
        $em = $this->getDoctrine()->getManager();
        $currentUser = $this->getCurrentOfficeUser()->getUser();

        $customer = $em->getRepository('TerraMarCustomerBundle:Customer')->find($id);

        if (!$customer) {
            throw $this->createNotFoundException('Unable to find Customer entity.');
        }

        $profile = $em->getRepository('TerraMarSalesBundle:CustomerSalesProfile')->findOneByCustomer($customer);

        $invoice = $em->getRepository('TerraMarSalesBundle:Invoice')->findOneBy(array('id' => $invoiceid));

        $form = $this->createForm(new InvoiceType($profile), $invoice);
        $form->bind($request);

        if ($form->isValid()) {
            $payments = $form->get('payments')->getData();

            /** @var \TerraMar\Bundle\SalesBundle\Helper\InvoiceHelper $helper */
            $helper = $this->get('terramar.sales.helper.invoice');

            $errors = array();

            foreach ($payments as $childIndex => $payment) {
                try {
                    $transaction = $helper->processPayment($invoice, $payment, $currentUser);
                    $em->persist($transaction);

                    if (!in_array(
                        $transaction->getStatus()->getValue(),
                        array(
                            ResultStatus::APPROVED,
                            ResultStatus::PROCESSED,
                            ResultStatus::PENDING
                        )
                    )) {
                        $errors[] = array(
                            'payment' => $payment,
                            'message' => $transaction->getResult()->getMessage()
                        );
                    }
                } catch (\Exception $e) {
                    $errors[] = array(
                        'payment' => $payment,
                        'message' => $e->getMessage()
                    );
                }
            }

            $em->persist($invoice);
            $em->flush();

            if (!empty($errors)) {
                // set in session and redirect to show
                $session = $this->getSession();
                $session->set(self::INVOICE_LAST_PROCESSED_KEY, $invoice->getId());
                foreach ($errors as &$error) {
                    $error['payment'] = $helper->serializePayment($error['payment']);
                }

                $session->set(self::INVOICE_LAST_PAYMENT_DATA_KEY, $errors);

                $this->getSession()->getFlashBag()->add('error', 'Some payments could not be processed.');
            } else {
                $this->getSession()->getFlashBag()->add('success', 'Invoice updated successfully.');
            }

            return $this->redirect($this->generateUrl('customer_invoice_show', array('id' => $id, 'invoiceid' => $invoiceid)));
        } else {
            return new Response(json_encode(array('type' => 'error', 'message' => $form->getErrorsAsString())));
        }
    }

    /**
     * Refunds a payment on an invoice
     *
     * @Route("customer/{id}/invoice/{invoiceid}/transaction/{transactionid}/refund", name="customer_invoice_refund", defaults={"_format"="json"})
     * @Method("POST")
     * @Template()
     * @Secure(roles="ROLE_INVOICE_REFUND")
     */
    public function refundAction($id, $invoiceid, $transactionid)
    {
        $em = $this->getDoctrine()->getManager();
        $currentUser = $this->getCurrentOfficeUser()->getUser();

        $customer = $em->getRepository('TerraMarCustomerBundle:Customer')->find($id);

        if (!$customer) {
            throw $this->createNotFoundException('Unable to find Customer entity.');
        }

        $profile = $em->getRepository('TerraMarSalesBundle:CustomerSalesProfile')->findOneByCustomer($customer);

        $invoice = $em->getRepository('TerraMarSalesBundle:Invoice')->find($invoiceid);

        $transaction = $em->getRepository('Orkestra\Transactor\Entity\Transaction')->find($transactionid);

        /** @var \TerraMar\Bundle\SalesBundle\Helper\InvoiceHelper $helper */
        $helper = $this->get('terramar.sales.helper.invoice');

        try {
            $refund = $helper->processRefund($invoice, $transaction, $currentUser);
        } catch (\Exception $e) {
            return new JsonErrorResponse($e->getMessage());
        }

        $em->persist($refund);
        $em->persist($invoice);
        $em->flush();

        $this->getSession()->getFlashBag()->set('success', 'The transaction has been refunded.');

        return new JsonReloadResponse();
    }

    /**
     * Deletes a Invoice entity.
     *
     * @Route("/{id}/delete", name="invoice_delete")
     * @Method("POST")
     * @Secure(roles="ROLE_CUSTOMER_WRITE")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('TerraMarSalesBundle:Invoice')->find($id);
        $entity->setActive(false);
        $em->persist($entity);
        $em->flush();

        $this->getSession()->getFlashBag()->add('success', 'Invoice Deleted successfully.');

        return $this->redirect($this->generateUrl('invoices'));
    }
}
