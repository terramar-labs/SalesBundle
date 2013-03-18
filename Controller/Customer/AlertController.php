<?php

namespace Terramar\Bundle\SalesBundle\Controller\Customer;

use Symfony\Component\HttpFoundation\Request;
use Terramar\Bundle\SalesBundle\Http\JsonReloadResponse;
use Terramar\Bundle\NotificationBundle\Model\Alert\AlertStatus;
use Terramar\Bundle\NotificationBundle\Model\Alert\AlertPriority;
use Terramar\Bundle\SalesBundle\Form\Customer\AlertType;
use Terramar\Bundle\SalesBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;


/**
 * Alert controller.
 *
 * @Route("/customer")
 */
class AlertController extends AbstractController
{
    /**
     * Displays a users alerts.
     *
     * @Route("/{id}/alerts", name="customer_alerts")
     * @Template()
     * @Secure(roles="ROLE_TACKBOARD_READ")
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $customer = $em->getRepository('TerramarCustomerBundle:Customer')->findOneByIdAndOffice($id, $this->getCurrentOffice());

        if (!$customer) {
            throw $this->createNotFoundException('Unable to locate Customer entity');
        }

        $profile = $em->getRepository('TerramarSalesBundle:CustomerSalesProfile')->findOneByCustomer($customer);

        if (!$profile) {
            throw $this->createNotFoundException('Unable to find Customer Sales Profile entity.');
        }

        $alerts = $em->getRepository('TerramarSalesBundle:Alert\CustomerAlert')->findActiveAlertsByProfile($profile);

        usort($alerts, function($a, $b) {
            $a = $a->getAlert();
            $b = $b->getAlert();

            if($a->getPriority() == $b->getPriority()) {
                return 0;
            } elseif ($a->getPriority() == AlertPriority::HIGH || ($a->getPriority() == AlertPriority::NORMAL && $b->getPriority() == AlertPriority::LOW)) {
                return -1;
            } else {
                return 1;
            }
        });

        return array(
            'entity' => $customer,
            'entities' => $alerts,
        );
    }

    /**
     * Displays a form to make a new Alert.
     *
     * @Route("/{id}/alert/new", name="customer_alert_new")
     * @Template()
     * @Secure(roles="ROLE_TACKBOARD_READ")
     */
    public function newAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $customer = $em->getRepository('TerramarCustomerBundle:Customer')->findOneByIdAndOffice($id, $this->getCurrentOffice());

        if (!$customer) {
            throw $this->createNotFoundException('Unable to locate Customer entity');
        }

        $form = $this->createForm(new AlertType());

        return array(
            'entity' => $customer,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates an Alert for a User
     *
     * @Route("/{id}/alert/create", name="customer_alert_create")
     * @Template("TerramarSalesBundle:Customer/Alert:new.html.twig")
     * @Secure(roles="ROLE_TACKBOARD_READ")
     */
    public function createAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $customer = $em->getRepository('TerramarCustomerBundle:Customer')->findOneByIdAndOffice($id, $this->getCurrentOffice());

        if (!$customer) {
            throw $this->createNotFoundException('Unable to locate Customer entity');
        }

        $factory = $this->get('terramar.notification.factory.alert');

        $form = $this->createForm(new AlertType());
        $form->bind($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $profile = $em->getRepository('TerramarSalesBundle:CustomerSalesProfile')->findOneByCustomer($customer);

            if (!$profile) {
                throw $this->createNotFoundException('Unable to find Customer Sales Profile entity.');
            }

            $entity = $factory->createAssignedAlert($this->getCurrentOfficeUser(), $profile, $data['name'], $data['description'], $data['priority']);

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->getSession()->getFlashBag()->add('success', 'The alert has been created successfully.');

            return $this->redirect($this->generateUrl('customer_tackboard', array('id' => $id)));
        }

        return array(
            'entity' => $customer,
            'form' => $form->createView(),
        );
    }

    /**
     * Updates an Alert for a User
     *
     * @Route("/{id}/alert/{alertid}/close", name="customer_alert_update", defaults={"_format"="json"})
     * @Method("POST")
     * @Template()
     * @Secure(roles="ROLE_TACKBOARD_READ")
     */
    public function updateAction($id, $alertid)
    {
        $em = $this->getDoctrine()->getManager();

        $customer = $em->getRepository('TerramarCustomerBundle:Customer')->findOneByIdAndOffice($id, $this->getCurrentOffice());

        if (!$customer) {
            throw $this->createNotFoundException('Unable to locate Customer entity');
        }

        $alert = $em->getRepository('TerramarSalesBundle:Alert\CustomerAlert')->find($alertid);

        if (!$alert) {
            throw $this->createNotFoundException('Unable to locate Alert entity');
        }

        $alert->getAlert()->setStatus(new AlertStatus(AlertStatus::COMPLETED));

        $em->persist($alert);
        $em->flush();

        $this->getSession()->getFlashBag()->add('success', 'The alert has been updated successfully.');

        return new JsonReloadResponse();
    }
}
