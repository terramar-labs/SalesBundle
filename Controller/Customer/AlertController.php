<?php

namespace TerraMar\Bundle\SalesBundle\Controller\Customer;

use Symfony\Component\HttpFoundation\Request;
use Pocomos\Bundle\ApplicationBundle\Http\JsonSuccessResponse;
use Pocomos\Bundle\ApplicationBundle\Http\JsonErrorResponse;
use Pocomos\Bundle\ApplicationBundle\Http\JsonReloadResponse;
use TerraMar\Bundle\SalesBundle\Entity\Alert\AlertStatus;
use TerraMar\Bundle\SalesBundle\Entity\Alert\AlertPriority;
use TerraMar\Bundle\SalesBundle\Form\Customer\AlertType;
use TerraMar\Bundle\SalesBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

        $customer = $em->getRepository('PocomosCustomerManagementBundle:Customer')->findOneByIdAndOffice($id, $this->getCurrentOffice());

        if (!$customer) {
            throw $this->createNotFoundException('Unable to locate Customer entity');
        }

        $profile = $em->getRepository('TerraMarSalesBundle:CustomerSalesProfile')->findOneByCustomer($customer);

        if (!$profile) {
            throw $this->createNotFoundException('Unable to find Customer Sales Profile entity.');
        }

        $alerts = $em->getRepository('TerraMarSalesBundle:Alert\CustomerAlert')->findActiveAlertsByProfile($profile);

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

        $customer = $em->getRepository('PocomosCustomerManagementBundle:Customer')->findOneByIdAndOffice($id, $this->getCurrentOffice());

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
     * @Route("/{id}/alert/create", name="customer_alert_create", defaults={"_format"="json"})
     * @Template()
     * @Secure(roles="ROLE_TACKBOARD_READ")
     */
    public function createAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $customer = $em->getRepository('PocomosCustomerManagementBundle:Customer')->findOneByIdAndOffice($id, $this->getCurrentOffice());

        if (!$customer) {
            throw $this->createNotFoundException('Unable to locate Customer entity');
        }

        $factory = $this->get('terramar.sales.factory.alert');

        $form = $this->createForm(new AlertType());
        $form->bind($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $profile = $em->getRepository('TerraMarSalesBundle:CustomerSalesProfile')->findOneByCustomer($customer);

            if (!$profile) {
                throw $this->createNotFoundException('Unable to find Customer Sales Profile entity.');
            }

            $entity = $factory->createAssignedAlert($this->getCurrentOfficeUser(), $profile, $data['name'], $data['description'], $data['priority']);

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->getSession()->getFlashBag()->add('success', 'The alert has been created successfully.');

            return new JsonReloadResponse();
        } else {
            return new JsonErrorResponse($form);
        }
    }

    /**
     * Updates an Alert for a User
     *
     * @Route("/{id}/alert/{alertid}/update", name="customer_alert_update", defaults={"_format"="json"})
     * @Method("POST")
     * @Template()
     * @Secure(roles="ROLE_TACKBOARD_READ")
     */
    public function updateAction($id, $alertid)
    {
        $em = $this->getDoctrine()->getManager();

        $customer = $em->getRepository('PocomosCustomerManagementBundle:Customer')->findOneByIdAndOffice($id, $this->getCurrentOffice());

        if (!$customer) {
            throw $this->createNotFoundException('Unable to locate Customer entity');
        }

        $alert = $em->getRepository('TerraMarSalesBundle:Alert\CustomerAlert')->find($alertid);

        if (!$alert) {
            throw $this->createNotFoundException('Unable to locate Alert entity');
        }

        $alert->getAlert()->setStatus(new AlertStatus(AlertStatus::COMPLETED));

        $em->persist($alert);
        $em->flush();

        $this->getSession()->getFlashBag()->add('success', 'The alert has been updated successfully.');

        return new JsonSuccessResponse();
    }
}
