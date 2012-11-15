<?php

namespace TerraMar\Bundle\SalesBundle\Controller\Customer;

use Symfony\Component\HttpFoundation\Request;
use TerraMar\Bundle\SalesBundle\Http\JsonReloadResponse;
use TerraMar\Bundle\SalesBundle\Entity\Alert\AlertStatus;
use TerraMar\Bundle\SalesBundle\Form\Customer\ToDoType;
use TerraMar\Bundle\SalesBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * To do controller.
 *
 * @Route("/customer")
 */
class ToDoController extends AbstractController
{
    /**
     * Displays all of a Customer's To Do list
     *
     * @Route("/{id}/to-dos", name="customer_todos")
     * @Template()
     * @Secure(roles="ROLE_TACKBOARD_READ")
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $customer = $em->getRepository('TerraMarCustomerBundle:Customer')->findOneByIdAndOffice($id, $this->getCurrentOffice());

        if (!$customer) {
            throw $this->createNotFoundException('Unable to locate Customer entity');
        }

        $profile = $em->getRepository('TerraMarSalesBundle:CustomerSalesProfile')->findOneByCustomer($customer);

        if (!$profile) {
            throw $this->createNotFoundException('Unable to find Customer Sales Profile entity.');
        }

        $todos = $em->getRepository('TerraMarSalesBundle:Alert\CustomerAlert')->findActiveTodosByProfile($profile);

        return array(
            'entity' => $customer,
            'entities' => $todos,
        );
    }

    /**
     * Displays a form to make a new Alert.
     *
     * @Route("/{id}/to-do/new", name="customer_todo_new")
     * @Template()
     * @Secure(roles="ROLE_TACKBOARD_READ")
     */
    public function newAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $customer = $em->getRepository('TerraMarCustomerBundle:Customer')->findOneByIdAndOffice($id, $this->getCurrentOffice());

        if (!$customer) {
            throw $this->createNotFoundException('Unable to locate Customer entity');
        }

        $form = $this->createForm(new ToDoType(), null, array('office' => $this->getCurrentOffice()));

        return array(
            'entity' => $customer,
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a Tackboard.
     *
     * @Route("/{id}/to-do/create", name="customer_todo_create")
     * @Template("TerraMarSalesBundle:Customer/ToDo:new.html.twig")
     * @Secure(roles="ROLE_CUSTOMER_WRITE")
     */
    public function createAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $customer = $em->getRepository('TerraMarCustomerBundle:Customer')->findOneByIdAndOffice($id, $this->getCurrentOffice());

        if (!$customer) {
            throw $this->createNotFoundException('Unable to locate Customer entity');
        }

        $profile = $em->getRepository('TerraMarSalesBundle:CustomerSalesProfile')->findOneByCustomer($customer);

        if (!$profile) {
            throw $this->createNotFoundException('Unable to find Customer Sales Profile entity.');
        }

        $factory = $this->get('terramar.sales.factory.alert');

        $form = $this->createForm(new ToDoType(), null, array('office' => $this->getCurrentOffice()));
        $form->bind($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $user = $this->getCurrentOfficeUser();

            if (!$data['assignedTo']) {
                $assignedTo = $user;
            } else {
                $assignedTo = $data['assignedTo'];
            }

            $todo = $factory->createAssignedTodo($user, $profile, $data['name'], $data['description'], $data['alertPriority'], $data['dueDate']);

            $userDescription = sprintf(
                '%s<br /><p>Customer: <a href="%s">%s</a></p>',
                $data['description'],
                $this->generateUrl('customer_show', array('id' => $id)),
                $customer
            );

            $userTodo = $factory->createAssignedTodo($user, $assignedTo, $data['name'], $userDescription, $data['alertPriority'], $data['dueDate']);

            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->persist($userTodo);
            $em->flush();

            $this->getSession()->getFlashBag()->add('success', 'To do created successfully.');

            return $this->redirect($this->generateUrl('customer_tackboard', array('id' => $id)));
        }

        return array(
            'entity' => $customer,
            'form' => $form->createView(),
        );
    }

    /**
     * Updates an Alert for a Customer
     *
     * @Route("/{id}/to-do/{alertid}/mark-as/{status}", name="customer_todo_update", defaults={"_format"="json"})
     * @Method("POST")
     * @Template()
     * @Secure(roles="ROLE_CUSTOMER_WRITE")
     */
    public function updateAction($id, $alertid, $status)
    {
        $em = $this->getDoctrine()->getManager();

        $alert = $em->getRepository('TerraMarSalesBundle:Alert\CustomerAlert')->find($alertid);

        if (!$alert) {
            throw $this->createNotFoundException('Unable to locate Alert entity');
        }

        if ($status == 'in-progress') {
            $status = AlertStatus::IN_PROGRESS;
        } elseif ($status == 'completed') {
            $status = AlertStatus::COMPLETED;
        } else {
            throw new \RuntimeException('Invalid status');
        }

        $alert->getAlert()->setStatus(new AlertStatus($status));

        $em->persist($alert);
        $em->flush();

        $this->getSession()->getFlashBag()->add('success', 'To do updated successfully.');

        return new JsonReloadResponse();
    }
}
