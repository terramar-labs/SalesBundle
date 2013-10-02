<?php

namespace Terramar\Bundle\SalesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Terramar\Bundle\SalesBundle\Form\AgreementType;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Terramar\Bundle\SalesBundle\Entity\Agreement;

/**
 * Agreement controller.
 *
 * @Route("/agreement")
 */
class AgreementController extends AbstractController
{
    /**
     * Lists all Agreement entities.
     *
     * @Route("s/", name="agreements")
     * @Template()
     * @Secure(roles="ROLE_AGREEMENT_READ")
     */
    public function indexAction(Request $request)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('TerramarSalesBundle:Agreement')->findByOffice($this->getCurrentOffice());

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Shows a Agreement
     *
     * @Route("/{id}/show.{_format}", name="agreement_show", defaults={"_format"="html"})
     * @Template
     * @Secure(roles="ROLE_AGREEMENT_READ")
     */
    public function showAction($id)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $agreement = $em->getRepository('TerramarSalesBundle:Agreement')->find($id);

        if (!$agreement) {
            throw $this->createNotFoundException('Unable to locate Agreement');
        }

        return array(
            'entity' => $agreement
        );
    }

    /**
     * Shows a form to create a new Agreement
     *
     * @Route("/new", name="agreement_new")
     * @Template
     * @Secure(roles="ROLE_AGREEMENT_WRITE")
     */
    public function newAction()
    {
        $form = $this->createForm('terramar_agreement');

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * Shows a form to create a new Agreement
     *
     * @Route("/create", name="agreement_create")
     * @Template("TerramarSalesBundle:Agreement:new.html.twig")
     * @Method("POST")
     * @Secure(roles="ROLE_AGREEMENT_WRITE")
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm('terramar_agreement');
        $form->bind($request);

        if ($form->isValid()) {
            $entity = $form->getData();
            $entity->setOffice($this->getCurrentOffice());

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->getSession()->getFlashBag()->set('success', 'The agreement has been created successfully.');

            return $this->redirect($this->generateUrl('agreements'));
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * Shows a form to edit an existing Agreement
     *
     * @Route("/{id}/edit", name="agreement_edit")
     * @Template
     * @Secure(roles="ROLE_AGREEMENT_WRITE")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TerramarSalesBundle:Agreement')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to locate Agreement');
        }

        $form = $this->createForm('terramar_agreement', $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }

    /**
     * Updates an existing Agreement
     *
     * @Route("/{id}/update", name="agreement_update")
     * @Template("TerramarSalesBundle:Agreement:edit.html.twig")
     * @Method("POST")
     * @Secure(roles="ROLE_AGREEMENT_WRITE")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TerramarSalesBundle:Agreement')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to locate Agreement');
        }

        $form = $this->createForm('terramar_agreement', $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->getSession()->getFlashBag()->set('success', 'The agreement has been updated successfully.');

            return $this->redirect($this->generateUrl('agreements'));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }
}
