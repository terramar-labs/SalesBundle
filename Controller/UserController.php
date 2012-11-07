<?php

namespace TerraMar\Bundle\SalesBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    JMS\SecurityExtraBundle\Annotation\Secure;
use Pocomos\Bundle\ApplicationBundle\Http\JsonErrorResponse;
use Orkestra\Bundle\ApplicationBundle\Entity\Group;
use Pocomos\Bundle\ApplicationBundle\Http\JsonReloadResponse;
use TerraMar\Bundle\SalesBundle\Entity\Salesperson;
use Pocomos\Bundle\PestManagementBundle\Entity\Technician;
use TerraMar\Bundle\SalesBundle\Form\UserType;
use TerraMar\Bundle\SalesBundle\Entity\OfficeUser;

use Symfony\Component\Form\FormError;

use Orkestra\Bundle\ApplicationBundle\Entity\User;

/**
 * User controller.
 *
 * @Route("/user")
 *
 * @todo This class creates a dependency on the Pest management bundle
 */
class UserController extends AbstractController
{
    /**
     * Lists all User entities.
     *
     * @Route("s/", name="orkestra_users")
     * @Template()
     * @Secure(roles="ROLE_USER_READ")
     */
    public function indexAction()
    {
        $this->requiresCurrentOffice();
        $office = $this->getCurrentOffice();

        $em = $this->getDoctrine()->getManager();

        $searchTerms = $this->getRequest()->get('searchTerms');
        if (!empty($searchTerms)) {
            $qb = $em->createQueryBuilder();
            $entities = $qb->select('u')
                ->from('TerraMarSalesBundle:OfficeUser', 'ou')
                ->from('OrkestraApplicationBundle:User', 'u')
                ->where('ou.user = u')
                ->andWhere('u.active = true')
                ->andWhere('ou.office = :office')
                ->andWhere($qb->expr()->orX(
                    $qb->expr()->like('u.firstName', ':searchTerms'),
                    $qb->expr()->like('u.lastName', ':searchTerms'),
                    $qb->expr()->like('u.username', ':searchTerms')
                ))
                ->setParameter('office', $office)
                ->setParameter('searchTerms', '%' . $searchTerms . '%')
                ->getQuery()
                ->getResult();
        }
        else {
            $entities = $em->createQueryBuilder()
                ->select('u')
                ->from('TerraMarSalesBundle:OfficeUser', 'ou')
                ->from('OrkestraApplicationBundle:User', 'u')
                ->where('ou.user = u')
                ->andWhere('u.active = true')
                ->andWhere('ou.office = :office')
                ->setParameter('office', $office)
                ->getQuery()
                ->getResult();
        }
        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a User entity.
     *
     * @Route("/{id}/show", name="orkestra_user_show")
     * @Template()
     * @Secure(roles="ROLE_USER_READ")
     */
    public function showAction($id)
    {
        $office = $this->getCurrentOffice();
        $em = $this->getDoctrine()->getManager();

        $user = $em->createQueryBuilder()
            ->select('u')
            ->from('TerraMarSalesBundle:OfficeUser ou, OrkestraApplicationBundle:User', 'u')
            ->where('u.active = true')
            ->andWhere('u.id = :id')
            ->andWhere('ou.office = :office')
            ->setParameters(array('office'=> $office, 'id' => $id))
            ->getQuery()
            ->getOneOrNullResult();

        if (!$user) {
            throw $this->createNotFoundException('Unable to locate User');
        }

        return array(
            'entity' => $user
        );
    }

    /**
     * Displays a form to create a new User entity.
     *
     * @Route("/new", name="orkestra_user_new")
     * @Template()
     * @Secure(roles="ROLE_USER_WRITE")
     */
    public function newAction()
    {
        $this->requiresCurrentOffice();

        $user = new User();
        $form = $this->createForm(new UserType(), $user);

        return array(
            'user' => $user,
            'form' => $form->createView()
        );
    }

    /**
     * Creates a new User entity.
     *
     * @Route("/create", name="orkestra_user_create", defaults={"_format"="json"})
     * @Method("post")
     * @Secure(roles="ROLE_USER_WRITE")
     */
    public function createAction()
    {
        $this->requiresCurrentOffice();

        $user = new User();
        $form = $this->createForm(new UserType(), $user);
        $form->bind($this->getRequest());

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $user->setPassword($encoder->encodePassword($user->getPassword(), $user->getSalt()));

            $officeUser = new OfficeUser($this->getCurrentOffice(), $user);

            $salesPerson = $form->get('salesperson')->getData();
            if ($salesPerson) {
                $salesPerson = new Salesperson($officeUser);
                $user->addGroup($em->getRepository('OrkestraApplicationBundle:Group')->findOneBy(array('role' => 'ROLE_SALESPERSON')));
                $em->persist($salesPerson);
            }

            $technician = $form->get('technician')->getData();
            if ($technician) {
                $license = $form->get('licensenumber')->getData();
                $technician = new Technician($license, $officeUser);
                $user->addGroup($em->getRepository('OrkestraApplicationBundle:Group')->findOneBy(array('role' => 'ROLE_TECHNICIAN')));
                $em->persist($technician);
            }

            $em->persist($user);
            $em->persist($officeUser);
            $em->flush();

            $this->get('session')->setFlash('success', 'The user has been created.');

            return $this->redirect($this->generateUrl('orkestra_users'));
        }

        return array(
            'user' => $user,
            'form' => $form->createView()
        );
    }

    /**
     * Reset Password Action
     *
     * @Route("/{id}/reset-password", name="orkestra_user_password_reset", defaults={"_format"="json"})
     * @Template()
     * @Secure(roles="ROLE_USER_WRITE")
     */
    public function resetPasswordAction($id)
    {
        $this->requiresCurrentOffice();

        $em = $this->getDoctrine()->getEntityManager();

        $user = $em->getRepository('OrkestraApplicationBundle:User')->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Unable to locate User');
        }

        $form = $this->createFormBuilder()
                     ->add('password', 'repeated', array(
                         'type' => 'password',
                         'invalid_message' => 'The passwords must match.',
                         'first_name' => 'password',
                         'second_name' => 'confirm',
                     ))
                     ->getForm();

        if ($this->getRequest()->getMethod() == 'POST') {
            $form->bindRequest($this->getRequest());

            if ($form->isValid()) {
                $data = $form->getData();

                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($user);
                $user->setPassword($encoder->encodePassword($data['password'], $user->getSalt()));

                $em->persist($user);
                $em->flush();

                $this->get('session')->setFlash('success', 'The password has been changed.');

                return $this->redirect($this->generateUrl('orkestra_user_show', array('id' => $id)));
            }
        }

        return array(
            'user' => $user,
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", name="orkestra_user_edit")
     * @Template()
     * @Secure(roles="ROLE_USER_WRITE")
     */
    public function editAction($id)
    {
        $this->requiresCurrentOffice();
        $office = $this->getCurrentOffice();

        $em = $this->getDoctrine()->getEntityManager();

        $user = $em->createQueryBuilder()
            ->select('u')
            ->from('TerraMarSalesBundle:OfficeUser ou, OrkestraApplicationBundle:User', 'u')
            ->where('u.active = true')
            ->andWhere('u.id = :id')
            ->andWhere('ou.office = :office')
            ->setParameters(array('office'=> $office, 'id' => $id))
            ->getQuery()
            ->getOneOrNullResult();

        if (!$user) {
            throw $this->createNotFoundException('Unable to locate User');
        }

        $form = $this->createEditFormType($user);

        return array(
            'user' => $user,
            'form' => $form->createView(),
        );
    }

    /**
     * Edits an existing User entity.
     *
     * @Route("/{id}/update", name="orkestra_user_update", defaults={"_format"="json"})
     * @Method("post")
     * @Secure(roles="ROLE_USER_WRITE")
     */
    public function updateAction($id)
    {
        $this->requiresCurrentOffice();
        $office = $this->getCurrentOffice();

        $em = $this->getDoctrine()->getEntityManager();

        $user = $em->createQueryBuilder()
            ->select('u')
            ->from('TerraMarSalesBundle:OfficeUser ou, OrkestraApplicationBundle:User', 'u')
            ->where('u.active = true')
            ->andWhere('u.id = :id')
            ->andWhere('ou.office = :office')
            ->setParameters(array('office'=> $office, 'id' => $id))
            ->getQuery()
            ->getOneOrNullResult();

        if (!$user) {
            throw $this->createNotFoundException('Unable to locate User');
        }

        $form = $this->createEditFormType($user);
        $form->bind($this->getRequest());

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $officeUser = $em->getRepository('TerraMarSalesBundle:OfficeUser')->findOneBy(array('user' => $id));

            $technicianRole = false;
            $salesPersonRole = false;
            $groups = $user->getGroups();

            $salesPersonChecked = $form->get('salesperson')->getData();
            $technicianChecked = $form->get('technician')->getData();

            if ($salesPersonChecked) {
                $salesPerson = $em->getRepository('TerraMarSalesBundle:Salesperson')->findOneBy(array('user' => $officeUser->getId()));
                if (!$salesPerson) {
                    $salesPerson = new Salesperson($officeUser);
                }
                $user->addGroup($em->getRepository('OrkestraApplicationBundle:Group')->findOneBy(array('role' => 'ROLE_SALESPERSON')));
                $em->persist($salesPerson);
            } else {
                $salesPerson = $em->getRepository('TerraMarSalesBundle:Salesperson')->findOneBy(array('user' => $officeUser->getId()));
                if ($salesPerson) {
                    $salesPerson->setActive(false);
                    $em->persist($salesPerson);
                }
            }

            if ($technicianChecked) {
                $license = $form->get('licensenumber')->getData();
                $technician = $em->getRepository('PocomosPestManagementBundle:Technician')->findOneBy(array('user' => $officeUser->getId()));
                if (!$technician) {
                    $technician = new Technician($license, $officeUser);
                }
                $user->addGroup($em->getRepository('OrkestraApplicationBundle:Group')->findOneBy(array('role' => 'ROLE_TECHNICIAN')));
                $technician->setLicenseNumber($license);
                $em->persist($technician);
            } else {
                $technician = $em->getRepository('PocomosPestManagementBundle:Technician')->findOneBy(array('user' => $officeUser->getId()));
                if ($technician) {
                    $technician->setActive(false);
                    $em->persist($technician);
                }
            }

            $em->persist($user);
            $em->persist($officeUser);
            $em->flush();

            $this->get('session')->setFlash('success', 'The user has been updated.');

            return $this->redirect($this->generateUrl('orkestra_users'));
        } else {
            return new JsonErrorResponse($form);
        }
    }

    /**
     * Creates an edit form
     *
     * @todo This is very hackish. Should create an intermediary model or at least move all this to the FormType itself
     *
     * @param \Orkestra\Bundle\ApplicationBundle\Entity\User $user
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createEditFormType(User $user)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new UserType(false), $user);

        $officeUser = $em->getRepository('TerraMarSalesBundle:OfficeUser')->findOneBy(array('user' => $user->getId()));

        // Set user's main group
        $createFilterCallable = function($type) {
            return function(Group $group) use ($type) {
                return $group->getRole() == $type;
            };
        };

        $roles = $user->getGroups();
        if (($groups = $roles->filter($createFilterCallable('ROLE_ADMIN'))) && !$groups->isEmpty()) {
            $form->get('groups')->setData($groups->first());
        } elseif (($groups = $roles->filter($createFilterCallable('ROLE_OWNER'))) && !$groups->isEmpty()) {
            $form->get('groups')->setData($groups->first());
        } elseif (($groups = $roles->filter($createFilterCallable('ROLE_BRANCH_MANAGER'))) && !$groups->isEmpty()) {
            $form->get('groups')->setData($groups->first());
        } elseif (($groups = $roles->filter($createFilterCallable('ROLE_SECRETARY'))) && !$groups->isEmpty()) {
            $form->get('groups')->setData($groups->first());
        }

        // Set technician and license
        $technician = $em->getRepository('PocomosPestManagementBundle:Technician')->findOneBy(array('user' => $officeUser->getId(), 'active' => true));
        if ($technician && $form->has('technician')) {
            $form->get('technician')->setData(true);
            $form->get('licensenumber')->setData($technician->getLicenseNumber());
        }

        // Set salesperson
        $salesperson = $em->getRepository('TerraMarSalesBundle:Salesperson')->findOneBy(array('user' => $officeUser->getId(), 'active' => true));
        if ($salesperson && $form->has('salesperson')) {
            $form->get('salesperson')->setData(true);
        }

        return $form;
    }

    /**
     * Deletes a User entity.
     *
     * @Route("/{id}/delete", name="orkestra_user_delete", defaults={"_format"="json"})
     * @Method("POST")
     */
    public function deleteAction($id)
    {
        $office = $this->getCurrentOffice();

        $em = $this->getDoctrine()->getManager();

        $user = $em->createQueryBuilder()
            ->select('u')
            ->from('TerraMarSalesBundle:OfficeUser ou, OrkestraApplicationBundle:User', 'u')
            ->where('u.active = true')
            ->andWhere('u.id = :id')
            ->andWhere('ou.office = :office')
            ->setParameters(array('office'=> $office, 'id' => $id))
            ->getQuery()
            ->getOneOrNullResult();

        $user->setActive(false);
        $officeUser = $em->getRepository('TerraMarSalesBundle:OfficeUser')->findOneBy(array('user' => $id));
        $officeUser->setActive(false);

        $em->persist($user);
        $em->persist($officeUser);
        $em->flush();

        return new JsonReloadResponse();
    }
}
