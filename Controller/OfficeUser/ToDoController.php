<?php

namespace TerraMar\Bundle\SalesBundle\Controller\OfficeUser;

use Symfony\Component\HttpFoundation\Request;
use Pocomos\Bundle\ApplicationBundle\Http\JsonErrorResponse;
use Pocomos\Bundle\ApplicationBundle\Http\JsonReloadResponse;
use TerraMar\Bundle\SalesBundle\Entity\Alert\AlertStatus;
use TerraMar\Bundle\SalesBundle\Form\OfficeUser\ToDoType;
use TerraMar\Bundle\SalesBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;


/**
 * ToDo controller.
 *
 * @Route("")
 */
class ToDoController extends AbstractController
{
    /**
     * Displays all ToDo's
     *
     * @Route("/{id}/todos", name="user_todos")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $office = $this->getCurrentOffice();

        $user = $em->getRepository('TerraMarSalesBundle:OfficeUser')->findOneBy(array('user' => $id, 'office' => $office));

        if (!$user) {
            throw $this->createNotFoundException('Unable to locate User');
        }

        $todos = $em->getRepository('TerraMarSalesBundle:Alert\OfficeUserAlert')->findToDosByAssignedTo($user);

        return array(
            'entities' => $todos,
            'entity' => $user
        );
    }

    /**
     * Displays a form to make a new Alert.
     *
     * @Route("/{id}/user-newtodo", name="user_new_todo")
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

        $form = $this->createForm(new ToDoType(), null, array('office' => $this->getCurrentOffice(), 'showAssignedTo' => false));

        return array(
            'form' => $form->createView(),
            'entity' => $user
        );
    }

    /**
     * Displays a Tackboard.
     *
     * @Route("/{id}/user-createtodo", name="user_create_todo", defaults={"_format"="json"})
     * @Template()
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

            $user = $em->getRepository('TerraMarSalesBundle:OfficeUser')->findOneBy(array('user' => $id, 'office' => $office));

            if (!$user) {
                throw $this->createNotFoundException('Unable to locate User');
            }

            $entity = $factory->createAssignedTodo($assignedBy, $user, $data['name'], $data['description'], $data['alertPriority'], $data['dueDate']);

            $em->persist($entity);
            $em->flush();

            $this->getSession()->getFlashBag()->add('success', 'The to-do has been created successfully.');

            return new JsonReloadResponse();
        } else {
            return new JsonErrorResponse($form);
        }
    }

    /**
     * Updates an Alert for a User
     *
     * @Route("{id}/user-todoupdate/{status}", name="user_update_todo", defaults={"_format"="json"})
     * @Method("POST")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function updateAction($id, $status)
    {
        $em = $this->getDoctrine()->getManager();

        $alert = $em->getRepository('TerraMarSalesBundle:Alert\OfficeUserAlert')->find($id);
        if ($status == 'InProgress') {
            $status = AlertStatus::IN_PROGRESS;
        } elseif ($status == 'Completed') {
            $status = AlertStatus::COMPLETED;
        }
        $alert->getAlert()->setStatus(new AlertStatus($status));

        $em->persist($alert);
        $em->flush();

        $this->getSession()->getFlashBag()->add('success', 'The to-do has been updated successfully.');

        return new JsonReloadResponse();
    }
}
