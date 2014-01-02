<?php

namespace Digs\InformationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Digs\InformationBundle\Entity\Information;
use Digs\InformationBundle\Form\InformationType;

/**
 * Information controller.
 *
 */
class InformationController extends Controller
{

    /**
     * Lists all Information entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $this->get('knp_paginator')->paginate(
			$em->getRepository('DigsInformationBundle:Information')->findOpenedDscQueryBuilder()->getQuery(),
			$request->query->get('page', 1),
			18
			);

        return $this->render('DigsInformationBundle:Information:index.html.twig', array(
            'entities' => $entities,
        ));
    }

	/**
     * Displays a form to create a new Information entity.
     *
     */
    public function newAction(Request $request)
    {
        $entity = new Information();
        $form   = $this->createCreateForm($entity);

		if ($request->isMethod('POST'))
		{
			$form->handleRequest($request);

			if ($form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($entity);
				$em->flush();

				return $this->redirect($this->generateUrl('information_show', array('id' => $entity->getId())));
			}
		}
        return $this->render('DigsInformationBundle:Information:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Information entity.
    *
    * @param Information $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Information $entity)
    {
        $form = $this->createForm(new InformationType(), $entity, array(
            'action' => $this->generateUrl('information_new'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Finds and displays a Information entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DigsInformationBundle:Information')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Information entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('DigsInformationBundle:Information:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Information entity.
     *
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DigsInformationBundle:Information')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Information entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);
		if ($request->isMethod('PUT'))
		{
			$editForm->handleRequest($request);

			if ($editForm->isValid()) {
				$em->flush();

				return $this->redirect($this->generateUrl('information_edit', array('id' => $id)));
			}
		}

        return $this->render('DigsInformationBundle:Information:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

	/**
    * Creates a form to edit a Information entity.
    *
    * @param Information $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Information $entity)
    {
        $form = $this->createForm(new InformationType(), $entity, array(
            'action' => $this->generateUrl('information_edit', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

	/**
     * Deletes a Information entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DigsInformationBundle:Information')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Information entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('information'));
    }

    /**
     * Creates a form to delete a Information entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('information_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
