<?php

namespace Terramar\Bundle\SalesBundle\Controller;

use Orkestra\Bundle\ReportBundle\Controller\Controller;
use Terramar\Bundle\SalesBundle\Report\AbstractOfficeReport;
use Terramar\Bundle\SalesBundle\Report\OfficeFilter;
use Terramar\Bundle\SalesBundle\Entity\Office;

abstract class AbstractController extends Controller
{
    const CURRENT_OFFICE_KEY = '__currentOfficeKey';

    private $currentOffice;

    private $currentUser;

    /**
     * Switches the office the User belongs during this session
     *
     * @param \Terramar\Bundle\SalesBundle\Entity\Office $office
     */
    protected function switchOffice(Office $office)
    {
        $session = $this->getSession();
        $session->set(self::CURRENT_OFFICE_KEY, $office->getId());
        $this->currentOffice = null;
    }

    /**
     * Gets the current user's associated office
     *
     * @throws \RuntimeException if the user has no associated office
     * @return \Terramar\Bundle\SalesBundle\Entity\Office|null
     */
    protected function getCurrentOffice()
    {
        if ($this->currentOffice) {
            return $this->currentOffice;
        }

        if ($this->getSession()->has(self::CURRENT_OFFICE_KEY)) {
            $this->currentOffice = $this->getDoctrine()->getManager()->find('TerramarSalesBundle:Office', $this->getSession()->get(self::CURRENT_OFFICE_KEY));
        } else {
            $user = $this->getUser();

            if ($user) {
                $officeRepository = $this->get('terramar.sales.repository.office');

                $this->currentOffice = $officeRepository->findOfficeByUser($user);
            }
        }

        if (!$this->currentOffice) {
            throw new \RuntimeException('This action requires that the current user be assigned to a company');
        }

        return $this->currentOffice;
    }

    /**
     * Gets the current Office User
     *
     * @return \Terramar\Bundle\SalesBundle\Entity\OfficeUser
     */
    protected function getCurrentOfficeUser()
    {
        if ($this->currentUser) {
            return $this->currentUser;
        }

        $user = $this->getUser();

        if (!$user) {
            return null;
        }

        $userRepository = $this->getDoctrine()->getManager()->getRepository('TerramarSalesBundle:OfficeUser');

        $this->currentUser = $userRepository->findOneBy(array('user' => $user->getId()));

        if (!$this->currentUser) {
            throw new \RuntimeException('This action requires that the current user be assigned to a company');
        }

        return $this->currentUser;
    }

    /**
     * Throws an exception if the current user has no assigned office
     *
     * @throws \RuntimeException
     * @deprecated
     */
    protected function requiresCurrentOffice()
    {
        if (!$this->getCurrentOffice()) {
            throw new \RuntimeException('This action requires that the current user be assigned to a company');
        }
    }

    /**
     * Creates a Presentation given a Presenter and Report
     *
     * @param \Orkestra\Bundle\ReportBundle\PresenterInterface|string $presenter If a string, an attempt to lookup the presenter will be done
     * @param \Orkestra\Bundle\ReportBundle\ReportInterface|string $report If a string, an attempt to lookup the report will be done
     *
     * @return \Orkestra\Bundle\ReportBundle\Presentation
     */
    protected function createPresentation($presenter, $report)
    {
        /** @var \Orkestra\Bundle\ReportBundle\ReportFactory $factory */
        $factory = $this->get('orkestra.report_factory');

        if (!is_object($presenter)) {
            $presenter = $factory->getPresenter($presenter);
        }

        if (!is_object($report)) {
            $report = $factory->getReport($report);
        }

        $preso = $factory->createPresentation($presenter, $report);

        if ($report instanceof AbstractOfficeReport) {
            $preso->prependFilter(new OfficeFilter($this->getCurrentOffice()));
        }

        return $preso;
    }

    /**
     * Locates an entity by its id, or throws a 404 if not found
     * 
     * @param string $className
     * @param int $id
     *
     * @return object
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function getEntityOr404($className, $id)
    {
        $entity = $this->getRepository($className)->find($id);
        
        if (!$entity) {
            $entityName = end(explode(':', end(explode('\\', $className))));
            
            throw $this->createNotFoundException(sprintf('Unable to locate %s', $entityName));
        }
        
        return $entity;
    }
}
