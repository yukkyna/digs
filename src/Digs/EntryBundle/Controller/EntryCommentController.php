<?php

namespace Digs\EntryBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Digs\EntryBundle\Entity\EntryComment;
use Digs\EntryBundle\Form\EntryCommentType;

/**
 * EntryComment controller.
 *
 */
class EntryCommentController extends Controller
{

    /**
     * Lists all EntryComment entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DigsEntryBundle:EntryComment')->findAll();

        return $this->render('DigsEntryBundle:EntryComment:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new EntryComment entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new EntryComment();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('entrycomment_show', array('id' => $entity->getId())));
        }

        return $this->render('DigsEntryBundle:EntryComment:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a EntryComment entity.
    *
    * @param EntryComment $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(EntryComment $entity)
    {
        $form = $this->createForm(new EntryCommentType(), $entity, array(
            'action' => $this->generateUrl('entrycomment_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new EntryComment entity.
     *
     */
    public function newAction()
    {
        $entity = new EntryComment();
        $form   = $this->createCreateForm($entity);

        return $this->render('DigsEntryBundle:EntryComment:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a EntryComment entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DigsEntryBundle:EntryComment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EntryComment entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('DigsEntryBundle:EntryComment:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing EntryComment entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DigsEntryBundle:EntryComment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EntryComment entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('DigsEntryBundle:EntryComment:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a EntryComment entity.
    *
    * @param EntryComment $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(EntryComment $entity)
    {
        $form = $this->createForm(new EntryCommentType(), $entity, array(
            'action' => $this->generateUrl('entrycomment_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing EntryComment entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DigsEntryBundle:EntryComment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EntryComment entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('entrycomment_edit', array('id' => $id)));
        }

        return $this->render('DigsEntryBundle:EntryComment:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a EntryComment entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DigsEntryBundle:EntryComment')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find EntryComment entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('entrycomment'));
    }

    /**
     * Creates a form to delete a EntryComment entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('entrycomment_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
