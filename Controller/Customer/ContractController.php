<?php

namespace TerraMar\Bundle\SalesBundle\Controller\Customer;

use Symfony\Component\HttpFoundation\Request;
use TerraMar\Bundle\SalesBundle\Form\ContractType;
use TerraMar\Bundle\SalesBundle\Controller\AbstractController;
use TerraMar\Bundle\SalesBundle\Form\NewContractType;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use TerraMar\Bundle\SalesBundle\Entity\Contract;

/**
 * Contract controller.
 *
 * @Route("/customer")
 */
class ContractController extends AbstractController
{
    /**
     * Lists all Contract entities.
     *
     * @Route("/{id}/contracts", name="customer_contracts")
     * @Template()
     * @Secure(roles="ROLE_CONTRACT_READ")
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $customer = $em->getRepository('TerraMarCustomerBundle:Customer')->findOneByIdAndOffice($id, $this->getCurrentOffice());

        if (!$customer) {
            throw $this->createNotFoundException('Unable to find Customer entity.');
        }

        /** @var $profile \TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile */
        $profile = $em->getRepository('TerraMarSalesBundle:CustomerSalesProfile')->findOneByCustomer($customer);

        if (!$profile) {
            throw $this->createNotFoundException('Unable to find Sales Profile entity.');
        }

        return array(
            'entity' => $customer,
            'entities' => $profile->getContracts(),
        );
    }

    /**
     * Shows a Contract
     *
     * @Route("/{id}/contract/{contractid}/show", name="customer_contract_show")
     * @Template
     * @Secure(roles="ROLE_CONTRACT_WRITE")
     */
    public function showAction($id, $contractid)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $customer = $em->getRepository('TerraMarCustomerBundle:Customer')->findOneByIdAndOffice($id, $this->getCurrentOffice());

        if (!$customer) {
            throw $this->createNotFoundException('Unable to find Customer entity.');
        }

        $contract = $em->getRepository('TerraMarSalesBundle:Contract')->find($contractid);

        if (!$contract) {
            throw $this->createNotFoundException('Unable to locate Contract');
        }

        return array(
            'entity' => $customer,
            'contract'=> $contract
        );
    }

    /**
     * Shows a form to create a new Contract
     *
     * @Route("/{id}/contract/new", name="customer_contract_new")
     * @Template
     * @Secure(roles="ROLE_CONTRACT_WRITE")
     */
    public function newAction($id)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $customer = $em->getRepository('TerraMarCustomerBundle:Customer')->findOneByIdAndOffice($id, $this->getCurrentOffice());

        if (!$customer) {
            throw $this->createNotFoundException('Unable to find Customer entity.');
        }

        $form = $this->createForm(new NewContractType(), null, array('office' => $this->getCurrentOffice()));

        return array(
            'entity' => $customer,
            'form' => $form->createView()
        );
    }

    /**
     * Shows a form to create a new Contract
     *
     * @Route("/{id}/contract/create", name="customer_contract_create")
     * @Template("TerraMarSalesBundle:Contract:new.html.twig")
     * @Method("POST")
     * @Secure(roles="ROLE_CONTRACT_WRITE")
     */
    public function createAction(Request $request, $id)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $customer = $em->getRepository('TerraMarCustomerBundle:Customer')->findOneByIdAndOffice($id, $this->getCurrentOffice());

        if (!$customer) {
            throw $this->createNotFoundException('Unable to find Customer entity.');
        }

        /** @var $profile \TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile */
        $profile = $em->getRepository('TerraMarSalesBundle:CustomerSalesProfile')->findOneByCustomer($customer);

        if (!$profile) {
            throw $this->createNotFoundException('Unable to find Sales Profile entity.');
        }

        $entity = new Contract();

        $form = $this->createForm(new NewContractType(), $entity, array('office' => $this->getCurrentOffice()));
        $form->bind($request);

        if ($form->isValid()) {
            $entity->setProfile($profile);

            /** @var $factory \TerraMar\Bundle\SalesBundle\Factory\ContractFactoryInterface */
            $factory = $this->get('terramar.sales.factory.contract');
            $factory->buildContract($entity);

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->getSession()->getFlashBag()->set('success', 'The contract has been created successfully.');

            return $this->redirect($this->generateUrl('customer_contracts', array('id' => $id)));
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * Shows a form to edit an existing Contract
     *
     * @Route("/{id}/contract/{contractid}/edit", name="customer_contract_edit")
     * @Template
     * @Secure(roles="ROLE_CONTRACT_WRITE")
     */
    public function editAction($id, $contractid)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $customer = $em->getRepository('TerraMarCustomerBundle:Customer')->findOneByIdAndOffice($id, $this->getCurrentOffice());

        if (!$customer) {
            throw $this->createNotFoundException('Unable to find Customer entity.');
        }

        $contract = $em->getRepository('TerraMarSalesBundle:Contract')->find($contractid);

        if (!$contract) {
            throw $this->createNotFoundException('Unable to locate Contract');
        }

        $form = $this->createForm(new ContractType(), $contract);

        return array(
            'entity' => $customer,
            'contract' => $contract,
            'form' => $form->createView()
        );
    }

    /**
     * Updates an existing Contract
     *
     * @Route("/{id}/contract/{contractid}/update", name="customer_contract_update")
     * @Template("TerraMarSalesBundle:Contract:edit.html.twig")
     * @Method("POST")
     * @Secure(roles="ROLE_CONTRACT_WRITE")
     */
    public function updateAction(Request $request, $id, $contractid)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $customer = $em->getRepository('TerraMarCustomerBundle:Customer')->findOneByIdAndOffice($id, $this->getCurrentOffice());

        if (!$customer) {
            throw $this->createNotFoundException('Unable to find Customer entity.');
        }

        $contract = $em->getRepository('TerraMarSalesBundle:Contract')->find($contractid);

        if (!$contract) {
            throw $this->createNotFoundException('Unable to locate Contract');
        }

        $form = $this->createForm(new ContractType(), $contract);
        $form->bind($request);

        if ($form->isValid()) {
            $em->persist($contract);
            $em->flush();

            $this->getSession()->getFlashBag()->set('success', 'The contract has been updated successfully.');

            return $this->redirect($this->generateUrl('customer_contracts', array('id' => $id)));
        }

        return array(
            'entity' => $customer,
            'contract' => $contract,
            'form' => $form->createView()
        );
    }
}
