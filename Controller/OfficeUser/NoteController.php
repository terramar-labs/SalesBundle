<?php

namespace TerraMar\Bundle\SalesBundle\Controller\OfficeUser;

use Symfony\Component\HttpFoundation\Request;
use Pocomos\Bundle\ApplicationBundle\Http\JsonErrorResponse;
use Pocomos\Bundle\ApplicationBundle\Http\JsonReloadResponse;
use TerraMar\Bundle\CustomerBundle\Entity\Note;
use TerraMar\Bundle\SalesBundle\Form\NoteType;
use TerraMar\Bundle\SalesBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Note controller.
 *
 * @Route("")
 */
class NoteController extends AbstractController
{
    /**
     * Displays a users Historical.
     *
     * @Route("/{id}/officeuser/notes", name="officeuser_notes")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $office = $this->getCurrentOffice();

        $user = $em->getRepository('TerraMarSalesBundle:OfficeUser')->findOneBy(array('user' => $id, 'office' => $office->getId()));

        if (!$user) {
            throw $this->createNotFoundException('Unable to locate User');
        }

        $notes = $user->getNotes()->toArray();

        usort($notes, function(Note $a, Note $b) {
            return $a->getDateCreated() < $b->getDateCreated();
        });

        return array(
            'entities' => $notes,
            'entity' => $user
        );
    }

    /**
     * Displays a users Historical.
     *
     * @Route("/{id}/officeuser/note/new", name="officeuser_note_new")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function newAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $office = $this->getCurrentOffice();

        $user = $em->getRepository('TerraMarSalesBundle:OfficeUser')->findOneBy(array('user' => $id, 'office' => $office));

        if (!$user) {
            throw $this->createNotFoundException('Unable to locate User');
        }

        $form = $this->createForm(new NoteType());

        return array(
            'form' => $form->createView(),
            'entity' => $user,
        );
    }

    /**
     * Displays a users Historical.
     *
     * @Route("/{id}/officeuser/note/create", name="officeuser_note_create", defaults={"_format"="json"})
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function createAction(Request $request, $id)
    {
        $note = new Note();
        $form = $this->createForm(new NoteType(), $note);
        $form->bind($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $office = $this->getCurrentOffice();

            $user = $em->getRepository('TerraMarSalesBundle:OfficeUser')->findOneBy(array('user' => $id, 'office' => $office));

            if (!$user) {
                throw $this->createNotFoundException('Unable to locate User');
            }

            $user->addNote($note);

            $em->persist($user);
            $em->flush();

            $this->getSession()->getFlashBag()->add('success', 'Note created successfully.');

            return new JsonReloadResponse();
        } else {
            return new JsonErrorResponse($form);
        }
    }
}
