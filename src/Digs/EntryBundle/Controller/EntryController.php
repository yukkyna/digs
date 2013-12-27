<?php

namespace Digs\EntryBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Digs\EntryBundle\Entity\Entry;
use Digs\EntryBundle\Form\EntryType;

/**
 * Entry controller.
 *
 */
class EntryController extends Controller
{

    /**
     * Lists all Entry entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $this->get('knp_paginator')->paginate(
			$em->getRepository('DigsEntryBundle:Entry')->findOpenedDscQueryBuilder()->getQuery(),
			$request->query->get('page', 1),
			18
			);

		return $this->render('DigsEntryBundle:Entry:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    public function topPanelAction($max = 10)
    {
		$entities = $this->getDoctrine()->getManager()
			->getRepository('DigsEntryBundle:Entry')->findOpenedDsc($max);

		return $this->render('DigsEntryBundle:Entry:toppanel.html.twig', array(
            'entities' => $entities,
        ));
    }

	/**
     * Creates a new Entry entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Entry();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('entry_show', array('id' => $entity->getId())));
        }

        return $this->render('DigsEntryBundle:Entry:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Entry entity.
    *
    * @param Entry $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Entry $entity)
    {
        $form = $this->createForm(new EntryType(), $entity, array(
            'action' => $this->generateUrl('entry_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Entry entity.
     *
     */
    public function newAction()
    {
        $entity = new Entry();
        $form   = $this->createCreateForm($entity);

        return $this->render('DigsEntryBundle:Entry:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Entry entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DigsEntryBundle:Entry')->findByIdOpenedJoinMember($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entry entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('DigsEntryBundle:Entry:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Entry entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DigsEntryBundle:Entry')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entry entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('DigsEntryBundle:Entry:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Entry entity.
    *
    * @param Entry $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Entry $entity)
    {
        $form = $this->createForm(new EntryType(), $entity, array(
            'action' => $this->generateUrl('entry_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
//
//        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Entry entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DigsEntryBundle:Entry')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entry entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('entry_edit', array('id' => $id)));
        }

        return $this->render('DigsEntryBundle:Entry:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Entry entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DigsEntryBundle:Entry')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Entry entity.');
            }
			$entity->setStatus(0);
            $em->persist($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('entry'));
    }

    /**
     * Creates a form to delete a Entry entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('entry_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
