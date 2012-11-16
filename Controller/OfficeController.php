<?php

namespace TerraMar\Bundle\SalesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Orkestra\Bundle\ApplicationBundle\Entity\File;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use TerraMar\Bundle\SalesBundle\Entity\Office;
use TerraMar\Bundle\SalesBundle\Form\OfficeType;

/**
 * Office controller.
 *
 * @Route("")
 */
class OfficeController extends AbstractController
{
    /**
     * Lists all Office entities.
     *
     * @Route("/companies/", name="companies")
     * @Template()
     * @Secure(roles="ROLE_COMPANY_READ")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('TerraMarSalesBundle:Office')->findParentOffices();

        return array(
            'entities' => $entities,
        );
    }


    /**
     * Finds and displays a Office entity.
     *
     * @Route("/company/{id}/show", name="company_show")
     * @Template()
     * @Secure(roles="ROLE_COMPANY_READ")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $office = $em->getRepository('TerraMarSalesBundle:Office')->find($id);

        if (!$office) {
            throw $this->createNotFoundException('Unable to find Office entity.');
        }

        return array(
            'entity' => $office
        );
    }

    /**
     * Lists all Office entities for a Parent.
     *
     * @Route("/companies/{id}/offices", name="company_offices")
     * @Template()
     * @Secure(roles="ROLE_COMPANY_READ")
     */
    public function indexOfficeAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $parent = $em->getRepository('TerraMarSalesBundle:Office')->find($id);

        if (!$parent) {
            throw $this->createNotFoundException('Unable to find Parent entity.');
        }

        $entities = $em->getRepository('TerraMarSalesBundle:Office')->findChildOfficesByParent($parent);

        return array(
            'entities' => $entities,
            'entity' => $parent,
        );
    }

    /**
     * Lists all Office entities that act as branches.
     *
     * @Route("/companies/offices", name="companies_offices")
     * @Template()
     * @Secure(roles="ROLE_COMPANY_READ")
     */
    public function indexOfficesAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('TerraMarSalesBundle:Office')->findAllChildOffices();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Displays a form to create a new Office entity.
     *
     * @Route("/company/new", name="company_new")
     * @Route("/company/{id}/office/new", name="office_new")
     * @Template()
     * @Secure(roles="ROLE_COMPANY_WRITE")
     */
    public function newAction($id = null)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Office();

        if ($id) {
            $parent = $em->getRepository('TerraMarSalesBundle:Office')->find($id);
            $entity->setParent($parent);
        }

        $form = $this->createForm(new OfficeType(), $entity, array('include_parent' => true));

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Office entity.
     *
     * @Route("/company/create", name="company_create")
     * @Method("POST")
     * @Template("TerraMarSalesBundle:Office:new.html.twig")
     * @Secure(roles="ROLE_COMPANY_WRITE")
     */
    public function createAction(Request $request)
    {
        $factory = $this->get('terramar.sales.factory.office');

        $entity = $factory->create();
        $form = $this->createForm(new OfficeType(), $entity, array('include_parent' => true));
        $form->bind($request);

        if ($form->isValid()) {
            if ($file = $form->get('logo')->getData()) {
                $helper = $this->get('terramar.sales.helper.file');

                $entity->setLogo($helper->createLogoFromUploadedFile($file, $entity));
            }

            $em = $this->getDoctrine()->getManager();

            $em->persist($entity);
            $em->flush();

            $this->getSession()->getFlashBag()->add('success', 'Company created successfully.');

            return $this->redirect($this->generateUrl('company_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Office entity.
     *
     * @Route("/company/{id}/edit", name="company_edit")
     * @Template()
     * @Secure(roles="ROLE_COMPANY_WRITE")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TerraMarSalesBundle:Office')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Office entity.');
        }

        $form = $this->createForm(new OfficeType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Edits an existing Office entity.
     *
     * @Route("/company/{id}/update", name="company_update")
     * @Method("POST")
     * @Template("TerraMarSalesBundle:Office:edit.html.twig")
     * @Secure(roles="ROLE_COMPANY_WRITE")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TerraMarSalesBundle:Office')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Office entity.');
        }

        $form = $this->createForm(new OfficeType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            if ($file = $form->get('logo')->getData()) {
                $helper = $this->get('terramar.sales.helper.file');

                $entity->setLogo($helper->createLogoFromUploadedFile($file, $entity));
            }

            $em->persist($entity);
            $em->flush();

            $this->getSession()->getFlashBag()->add('success', 'Company updated successfully.');

            return $this->redirect($this->generateUrl('company_show', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Deletes a Company entity.
     *
     * @todo Should this be allowed?
     *
     * @Route("/{id}/delete", name="branch_delete", defaults={"_format"="json"})
     * @Method("POST")
     */
    public function deleteBranchAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('TerraMarSalesBundle:Office')->find($id);
        $entity->setActive(false);
        $em->persist($entity);
        $em->flush();

        $this->getSession()->getFlashBag()->add('success', 'Company deleted successfully.');

        return new Response(json_encode(array('type' => 'success', 'reload' => true)));
    }
}
