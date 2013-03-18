<?php

namespace Terramar\Bundle\SalesBundle\Controller\OfficeUser;

use Symfony\Component\HttpFoundation\Request;
use Terramar\Bundle\SalesBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Alert  history controller.
 *
 * @Route("")
 */
class AlertHistoryController extends AbstractController
{
    /**
     * Displays a users Historical.
     *
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function indexAction($id = null)
    {
        $em = $this->getDoctrine()->getManager();
        $office = $this->getCurrentOffice();

        if (!$id) {
            $user = $this->getCurrentOfficeUser();
        } else {
            $user = $em->getRepository('TerramarSalesBundle:OfficeUser')->findOneBy(array('user' => $id, 'office' => $office));
        }

        if (!$user) {
            throw $this->createNotFoundException('Unable to locate User');
        }

        $alerts = $em->getRepository('TerramarSalesBundle:Alert\OfficeUserAlert')->findAllAlertHistoryByUser($user);

        return array(
            'entities' => $alerts,
        );
    }

}
