<?php

namespace TerraMar\Bundle\SalesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Pocomos\Bundle\ApplicationBundle\Http\JsonReloadResponse;
use Orkestra\Transactor\Entity\Credentials;
use Orkestra\Transactor\Entity\Transaction\NetworkType;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\DBALException;
use TerraMar\Bundle\SalesBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use TerraMar\Bundle\SalesBundle\Entity\Office\OfficeConfiguration;
use TerraMar\Bundle\SalesBundle\Form\Office\OfficeConfigurationType;

/**
 * OfficeConfiguration controller.
 *
 * @Route("/office-configuration")
 */
class OfficeConfigurationController extends AbstractController
{
    protected static $themes = array(
        'blue',
        'earth',
        'green',
        'holidays',
        'horizon',
        'neutral',
        'orange',
        'red',
        'solitude',
        'vegas'
    );

    /**
     * Displays a form to edit an existing OfficeConfiguration entity.
     *
     * @Route("/edit", name="officeconfiguration_edit")
     * @Template()
     * @Secure(roles="ROLE_OFFICE_WRITE")
     */
    public function editAction(Request $request)
    {
        $office = $this->getCurrentOffice();

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TerraMarSalesBundle:Office\OfficeConfiguration')->findOneBy(array('office' => $office->getId()));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pest Office Configuration entity.');
        }

        $this->updateCredentialsFromRequest($entity, $request);

        $form = $this->createForm(new OfficeConfigurationType($this->get('orkestra.form_helper')), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    private function updateCredentialsFromRequest(OfficeConfiguration $entity, Request $request)
    {
        $cardTransactor = $request->get('card-transactor');
        if (!empty($cardTransactor)) {
            if (!$entity->getCardCredentials()) {
                $entity->setCardCredentials(new Credentials());
            }

            $entity->getCardCredentials()->setTransactor($cardTransactor);
        }

        $achTransactor = $request->get('ach-transactor');
        if (!empty($achTransactor)) {
            if (!$entity->getAchCredentials()) {
                $entity->setAchCredentials(new Credentials());
            }

            $entity->getAchCredentials()->setTransactor($achTransactor);
        }
    }

    /**
     * Edits an existing OfficeConfiguration entity.
     *
     * @Route("/update", name="officeconfiguration_update", defaults={"_format"="json"})
     * @Method("POST")
     * @Secure(roles="ROLE_OFFICE_WRITE")
     */
    public function updateAction(Request $request)
    {
        $office = $this->getCurrentOffice();

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TerraMarSalesBundle:Office\OfficeConfiguration')->findOneBy(array('office' => $office->getId()));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pest Office Configuration entity.');
        }

        $this->updateCredentialsFromRequest($entity, $request);

        $form = $this->createForm(new OfficeConfigurationType($this->get('orkestra.form_helper')), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->getSession()->getFlashBag()->add('success', 'Office configuration updated successfully.');

            return new Response(json_encode(array('type' => 'success', 'reload' => true)));
        }

        return new Response(json_encode(array('type' => 'error', 'message' => 'An error occurred while updating the office configuration')));
    }

    /**
     * Edits an existing OfficeConfiguration entity.
     *
     * @Route("/appearance", name="officeconfiguration_theme_edit")
     * @Template()
     * @Secure(roles="ROLE_OFFICE_WRITE")
     */
    public function editAppearanceAction(Request $request)
    {
        $office = $this->getCurrentOffice();

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TerraMarSalesBundle:Office\OfficeConfiguration')->findOneBy(array('office' => $office->getId()));

        return array(
            'entity' => $entity,
            'themes' => self::$themes
        );
    }

    /**
     * Edits an existing OfficeConfiguration entity.
     *
     * @Route("/appearance/update", name="officeconfiguration_theme_update", defaults={"_format"="json"})
     * @Method("POST")
     * @Secure(roles="ROLE_OFFICE_WRITE")
     */
    public function updateAppearanceAction(Request $request)
    {
        $office = $this->getCurrentOffice();

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TerraMarSalesBundle:Office\OfficeConfiguration')->findOneBy(array('office' => $office->getId()));

        $theme = $request->get('theme');

        if (!in_array($theme, self::$themes)) {
            $theme = 'blue';
        }

        $entity->setTheme($theme);

        $em->persist($entity);
        $em->flush();

        $this->getSession()->getFlashBag()->set('success', 'The theme has been changed. The theme will be applied when you next login.');

        return new JsonReloadResponse();
    }
}
