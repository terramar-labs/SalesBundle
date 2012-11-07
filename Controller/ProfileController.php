<?php

namespace TerraMar\Bundle\SalesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use TerraMar\Bundle\SalesBundle\Form\Profile\ChangePasswordType;
use Pocomos\Bundle\ApplicationBundle\Http\JsonErrorResponse;
use Pocomos\Bundle\ApplicationBundle\Http\JsonReloadResponse;
use TerraMar\Bundle\SalesBundle\Form\Profile\ProfileType;
use Symfony\Component\Form\FormError;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Profile controller.
 *
 * @Route("/profile")
 */
class ProfileController extends AbstractController
{
    /**
     * Shows the user's profile
     *
     * @Route("/", name="orkestra_profile")
     * @Secure(roles="ROLE_USER")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $officeUser = $this->getCurrentOfficeUser();
        $user = $officeUser->getUser();

        $form = $this->createForm(new ProfileType(), $user);

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $this->get('session')->setFlash('success', 'Your profile has been updated.');

                return new JsonReloadResponse();
            } else {
                return new JsonErrorResponse($form);
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Show the change password form
     *
     * @Route("/change-password", name="orkestra_profile_password")
     * @Secure(roles="ROLE_USER")
     * @Template()
     */
    public function passwordAction(Request $request)
    {
        $officeUser = $this->getCurrentOfficeUser();
        $user = $officeUser->getUser();

        $form = $this->createForm(new ChangePasswordType(), array());

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            $data = $form->getData();

            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $current = $encoder->encodePassword($data['current'], $user->getSalt());

            if ($current !== $user->getPassword()) {
                $form->get('current')->addError(new FormError('Current password is not correct'));
            }

            if ($form->isValid()) {
                $user->setPassword($encoder->encodePassword($data['password'], $user->getSalt()));

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $this->get('session')->setFlash('success', 'Your password has been changed.');

                return new JsonReloadResponse();
            } else {
                return new JsonErrorResponse($form);
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }
}
