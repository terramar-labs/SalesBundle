<?php

namespace Terramar\Bundle\SalesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Pocomos\Bundle\ApplicationBundle\Http\JsonReloadResponse;
use Orkestra\Transactor\Entity\Credentials;
use Orkestra\Transactor\Entity\Transaction\NetworkType;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\DBALException;
use Terramar\Bundle\SalesBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Terramar\Bundle\SalesBundle\Entity\Office\OfficeConfiguration;
use Terramar\Bundle\SalesBundle\Form\Office\OfficeConfigurationType;

/**
 * OfficeConfiguration controller.
 *
 * @Route("/settings")
 */
class OfficeConfigurationController extends AbstractController
{
    /**
     * Displays a form to edit an existing OfficeConfiguration entity.
     *
     * @Route("/edit", name="officeconfiguration_edit")
     * @Template()
     * @Secure(roles="ROLE_SETTINGS_WRITE")
     */
    public function editAction(Request $request)
    {
        $office = $this->getCurrentOffice();

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TerramarSalesBundle:Office\OfficeConfiguration')->findOneBy(array('office' => $office->getId()));

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
     * @Route("/update", name="officeconfiguration_update")
     * @Template("TerramarSalesBundle:OfficeConfiguration:edit.html.twig")
     * @Method("POST")
     * @Secure(roles="ROLE_SETTINGS_WRITE")
     */
    public function updateAction(Request $request)
    {
        $office = $this->getCurrentOffice();

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TerramarSalesBundle:Office\OfficeConfiguration')->findOneBy(array('office' => $office->getId()));

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

            return $this->redirect($this->generateUrl('officeconfiguration_edit'));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }
}
