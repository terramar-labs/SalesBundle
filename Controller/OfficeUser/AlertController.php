<?php

namespace TerraMar\Bundle\SalesBundle\Controller\OfficeUser;

use Symfony\Component\HttpFoundation\Request;
use Pocomos\Bundle\ApplicationBundle\Http\JsonReloadResponse;
use Pocomos\Bundle\ApplicationBundle\Http\JsonErrorResponse;
use TerraMar\Bundle\SalesBundle\Entity\Alert\AlertStatus;
use TerraMar\Bundle\SalesBundle\Entity\Alert\AlertPriority;
use TerraMar\Bundle\SalesBundle\Form\OfficeUser\AlertType;
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
 * @Route("")
 */
class AlertController extends AbstractController
{
    /**
     * Displays a users alerts.
     *
     * @Route("/{id}/alerts", name="user_alerts")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $office = $this->getCurrentOffice();

        $user = $em->getRepository('TerraMarSalesBundle:OfficeUser')->findOneBy(array('user' => $id, 'office' => $office->getId()));

        if (!$user) {
            throw $this->createNotFoundException('Unable to locate User');
        }

        $alerts = $em->getRepository('TerraMarSalesBundle:Alert\OfficeUserAlert')->findActiveAlertsByAssignedTo($user);

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
            'entities' => $alerts,
            'entity' => $user
        );
    }

    /**
     * Displays a form to make a new Alert.
     *
     * @Route("/{id}/user-new-alert", name="user_new_alert")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function newAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $office = $this->getCurrentOffice();

        $user = $em->getRepository('TerraMarSalesBundle:OfficeUser')->findOneBy(array('user' => $id, 'office' => $office));

        if (!$user) {
            throw $this->createNotFoundException('Unable to locate User');
        }

        $form = $this->createForm(new AlertType(), null, array('office' => $this->getCurrentOffice(), 'showAssignedTo' => false));

        return array(
            'form' => $form->createView(),
            'entity' => $user
        );
    }

    /**
     * Creates an Alert for a User
     *
     * @Route("/{id}/user-create-alert", name="user_create_alert", defaults={"_format"="json"})
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function createAction(Request $request, $id)
    {
        $factory = $this->get('terramar.sales.factory.alert');

        $form = $this->createForm(new AlertType(), null, array('office' => $this->getCurrentOffice(), 'showAssignedTo' => false));
        $form->bind($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $assignedBy = $this->getCurrentOfficeUser();

            $em = $this->getDoctrine()->getManager();
            $office = $this->getCurrentOffice();

            $user = $em->getRepository('TerraMarSalesBundle:OfficeUser')->findOneBy(array('user' => $id, 'office' => $office));

            if (!$user) {
                throw $this->createNotFoundException('Unable to locate User');
            }

            $entity = $factory->createAssignedAlert($assignedBy, $user, $data['name'], $data['description'], $data['alertPriority']);

            $em->persist($entity);
            $em->flush();

            $this->getSession()->getFlashBag()->add('success', 'Alert created successfully.');

            return new JsonReloadResponse();
        } else {
            return new JsonErrorResponse($form);
        }
    }

    /**
     * Updates an Alert for a User
     *
     * @Route("{id}/user-update-alert", name="user_update_alert", defaults={"_format"="json"})
     * @Method("POST")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $alert = $em->getRepository('TerraMarSalesBundle:Alert\OfficeUserAlert')->find($id);
        $alert->getAlert()->setStatus(new AlertStatus(AlertStatus::COMPLETED));

        $em->persist($alert);
        $em->flush();

        $this->getSession()->getFlashBag()->add('success', 'Alert updated successfully.');

        return new JsonReloadResponse();
    }
}
