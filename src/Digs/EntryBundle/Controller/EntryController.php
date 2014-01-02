<?php

namespace Digs\EntryBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Digs\EntryBundle\Entity\Entry;
use Digs\EntryBundle\Form\EntryType;
use Digs\EntryBundle\Entity\EntryComment;
use Digs\EntryBundle\Form\EntryCommentType;

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

    public function profilePanelAction($profile, $max = 10)
    {
		$entities = $this->getDoctrine()->getManager()
			->getRepository('DigsEntryBundle:Entry')->findOpenedByProfileDsc($profile, $max);

		return $this->render('DigsEntryBundle:Entry:profilepanel.html.twig', array(
            'entities' => $entities,
        ));
    }

	/**
     * Displays a form to create a new Entry entity.
     *
     */
    public function newAction(Request $request)
    {
        $entity = new Entry();
        $form   = $this->createCreateForm($entity);
		
		if ($request->isMethod('POST'))
		{
			$form->handleRequest($request);
			if ($form->isValid()) {
				$entity->setMember($this->getUser());
				$entity->setStatus(1);
				$em = $this->getDoctrine()->getManager();
				$em->persist($entity);
				$em->flush();

				return $this->redirect($this->generateUrl('entry_show', array('id' => $entity->getId())));
			}
		}
        return $this->render('DigsEntryBundle:Entry:edit.html.twig', array(
            'entity' => $entity,
            'edit_form'   => $form->createView(),
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
            'action' => $this->generateUrl('entry_new'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Finds and displays a Entry entity.
     *
     */
    public function showAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DigsEntryBundle:Entry')->findByIdOpenedJoinMember($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entry entity.');
        }

		$comment = new EntryComment();
		$comment->setEntry($entity);
		$comment->setMember($this->getUser());
		$comment->setStatus(1);
        $commentForm = $this->createForm(new EntryCommentType(), $comment, array(
            'action' => $this->generateUrl('entry_show', array('id' => $entity->getId())),
            'method' => 'POST',
        ));
		if ($request->isMethod('POST'))
		{
	        $commentForm->handleRequest($request);
			if ($commentForm->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($comment);
				$em->flush();
			}
            return $this->redirect($this->generateUrl('entry_show', array('id' => $entity->getId())));
		}

        return $this->render('DigsEntryBundle:Entry:show.html.twig', array(
            'entity'      => $entity,
			'comment_form' => $commentForm->createView()
			));
    }

    /**
     * Displays a form to edit an existing Entry entity.
     *
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DigsEntryBundle:Entry')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entry entity.');
        }
		if ($entity->getMember()->getId() !== $this->getUser()->getId())
		{
            throw $this->createNotFoundException('Unable to edit Entry.');
		}

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);
		if ($request->isMethod('PUT'))
		{
			$editForm->handleRequest($request);

			if ($editForm->isValid()) {
				$em->flush();

				return $this->redirect($this->generateUrl('entry_show', array('id' => $id)));
			}
		}

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
            'action' => $this->generateUrl('entry_edit', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
//
//        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
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
