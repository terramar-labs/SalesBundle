<?php

namespace Terramar\Bundle\SalesBundle\Controller\OfficeUser;

use Symfony\Component\HttpFoundation\Request;
use Terramar\Bundle\SalesBundle\Form\OfficeUser\TackType;
use Terramar\Bundle\SalesBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;


/**
 * Tackboard controller.
 *
 * @Route("")
 */
class TackboardController extends AbstractController
{
    /**
     * Displays a Tackboard.
     *
     * @Route("/notifications", name="tackboard")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function indexAction()
    {
        $user = $this->getCurrentOfficeUser();

        return array(
            'entity' => $user,
        );
    }

    /**
     * Displays a Form to post on someone else's tackboard
     *
     * @Route("/tack/user/new", name="tack_user_new")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function newTackSomeoneAction()
    {
        $form = $this->createForm(new TackType(), null, array('office' => $this->getCurrentOffice()));

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a post on someone else's tackboard.
     *
     * @Route("/tack/user/create", name="tack_user_create", defaults={"_format"="json"})
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function createTackSomeoneAction(Request $request)
    {
        $factory = $this->get('terramar.sales.factory.alert');

        $form = $this->createForm(new TackType(), null, array('office' => $this->getCurrentOffice()));
        $form->bind($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $user = $this->getCurrentOfficeUser();

            if ($data['alertType'] == 'Alert') {
                $entity = $factory->createAssignedAlert($user, $data['assignedTo'], $data['name'], $data['description'], $data['alertPriority']);
            } else {
                $entity = $factory->createAssignedTodo($user, $data['assignedTo'], $data['name'], $data['description'], $data['alertPriority'], $data['dueDate']);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->getSession()->getFlashBag()->add('success', 'Post created successfully.');

            return new JsonReloadResponse();
        } else {
            return new JsonErrorResponse($form);
        }
    }
}
