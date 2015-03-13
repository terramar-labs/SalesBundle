<?php

namespace Terramar\Bundle\SalesBundle\Controller\OfficeUser;

use Symfony\Component\HttpFoundation\Request;
use Terramar\Bundle\SalesBundle\Http\JsonReloadResponse;
use Terramar\Bundle\SalesBundle\Form\OfficeUser\ToDoType;
use Terramar\Bundle\SalesBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Terramar\Bundle\SalesBundle\Model\Alert\AlertStatus;


/**
 * ToDo controller.
 *
 * @Route("/user")
 */
class ToDoController extends AbstractController
{
    /**
     * Displays all ToDo's
     *
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $office = $this->getCurrentOffice();

        $user = $em->getRepository('TerramarSalesBundle:OfficeUser')->findOneBy(array('user' => $id, 'office' => $office));

        if (!$user) {
            throw $this->createNotFoundException('Unable to locate User');
        }

        $todos = $em->getRepository('TerramarSalesBundle:Alert\OfficeUserAlert')->findToDosByAssignedTo($user);

        return array(
            'entities' => $todos,
            'entity' => $user
        );
    }

    /**
     * Displays a form to make a new Alert.
     *
     * @Route("/{id}/to-do/new", name="user_new_todo")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function newAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $office = $this->getCurrentOffice();

        $user = $em->getRepository('TerramarSalesBundle:OfficeUser')->findOneBy(array('user' => $id, 'office' => $office));

        if (!$user) {
            throw $this->createNotFoundException('Unable to locate User');
        }

        $form = $this->createForm(new ToDoType(), null, array('office' => $this->getCurrentOffice(), 'showAssignedTo' => false));

        return array(
            'form' => $form->createView(),
            'entity' => $user
        );
    }

    /**
     * Displays a Tackboard.
     *
     * @Route("/{id}/to-do/create", name="user_create_todo")
     * @Template("TerramarSalesBundle:OfficeUser/ToDo:new.html.twig")
     * @Secure(roles="ROLE_USER")
     */
    public function createAction(Request $request, $id)
    {
        $factory = $this->get('terramar.sales.factory.alert');

        $form = $this->createForm(new ToDoType(), null, array('office' => $this->getCurrentOffice(), 'showAssignedTo' => false));
        $form->bind($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $assignedBy = $this->getCurrentOfficeUser();

            $em = $this->getDoctrine()->getManager();
            $office = $this->getCurrentOffice();

            $user = $em->getRepository('TerramarSalesBundle:OfficeUser')->findOneBy(array('user' => $id, 'office' => $office));

            if (!$user) {
                throw $this->createNotFoundException('Unable to locate User');
            }

            $entity = $factory->createAssignedTodo($assignedBy, $user, $data['name'], $data['description'], $data['alertPriority'], $data['dueDate']);

            $em->persist($entity);
            $em->flush();

            $this->getSession()->getFlashBag()->add('success', 'The to-do has been created successfully.');

            if ($this->get('security.context')->isGranted('ROLE_USER_READ')) {
                return $this->redirect($this->generateUrl('orkestra_user_show', array('id' => $id)));
            } else {
                return $this->redirect($this->generateUrl('tackboard'));
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * Updates an Alert for a User
     *
     * @Route("/{id}/to-dos/mark-as/{status}", name="user_update_todo", defaults={"_format"="json"})
     * @Method("POST")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function updateAction($id, $status)
    {
        $em = $this->getDoctrine()->getManager();

        $alert = $em->getRepository('TerramarSalesBundle:Alert\OfficeUserAlert')->find($id);
        if ($status == 'in-progress') {
            $status = AlertStatus::IN_PROGRESS;
        } elseif ($status == 'completed') {
            $status = AlertStatus::COMPLETED;
        }
        $alert->getAlert()->setStatus(new AlertStatus($status));

        $em->persist($alert);
        $em->flush();

        $this->getSession()->getFlashBag()->add('success', 'The to-do has been updated successfully.');

        return new JsonReloadResponse();
    }
}
