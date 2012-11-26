<?php

namespace TerraMar\Bundle\SalesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use TerraMar\Bundle\SalesBundle\Form\OfficeSelectType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Context controller.
 *
 * @Route("")
 */
class ContextController extends AbstractController
{
    /**
     * @Route("/logo", name="context_logo")
     * @Template
     */
    public function logoAction()
    {
        $office = $this->getCurrentOffice();

        return array(
            'logo' => $office->getLogo()
        );
    }

    /**
     * Creates Select Dropdown
     *
     * @Route("/office-select", name="context_select")
     * @Template()
     */
    public function officeSelectAction()
    {
        $office = $this->getCurrentOffice();
        $user = $this->getCurrentOfficeUser();

        $form = $this->createForm(new OfficeSelectType($office, $user));
        $form->get('office')->setData($office);

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Switches offices
     *
     * @Route("/office-switch", name="context_switch", defaults={"_format"="json"})
     * @Template()
     */
    public function switchContextAction(Request $request)
    {
        $office = $this->getCurrentOffice();
        $user = $this->getCurrentOfficeUser();

        $form = $this->createForm(new OfficeSelectType($office, $user));
        $form->bind($request);

        if ($form->isValid()) {
            $office = $form->get('office')->getData();
            $this->switchOffice($office);

            $this->getSession()->getFlashBag()->set('success', 'The current office has been changed.');

            return $this->redirect($this->generateUrl('customers'));
        }

        return array();
    }
}
