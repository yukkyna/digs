<?php

namespace Digs\InformationBundle\Controller;

use Digs\InformationBundle\Entity\Information;
use Digs\InformationBundle\Form\InformationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
		if (false === $this->get('security.context')->isGranted('ROLE_INFORMATION'))
		{
			throw new AccessDeniedException();
		}
		
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
    * @return Form The form
    */
    private function createCreateForm(Information $entity)
    {
		$form = $this->createForm(new InformationType(), $entity, array(
            'action' => $this->generateUrl('information_new'),
            'method' => 'POST',
        ));
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
		if (false === $this->get('security.context')->isGranted('ROLE_INFORMATION'))
		{
			throw new AccessDeniedException();
		}

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

				return $this->redirect($this->generateUrl('information_show', array('id' => $id)));
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
    * @return Form The form
    */
    private function createEditForm(Information $entity)
    {
        $form = $this->createForm(new InformationType(), $entity, array(
            'action' => $this->generateUrl('information_edit', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        return $form;
    }

	/**
     * Deletes a Information entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
		if (false === $this->get('security.context')->isGranted('ROLE_INFORMATION'))
		{
			throw new AccessDeniedException();
		}

		$form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DigsInformationBundle:Information')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Information entity.');
            }
			$entity->setStatus(0);
			$em->persist($entity);
			$em->flush();
        }

        return $this->redirect($this->generateUrl('information'));
    }

    /**
     * Creates a form to delete a Information entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('information_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

	public function topPanelAction($max = 10)
    {
		$entities = $this->getDoctrine()->getManager()
			->getRepository('DigsInformationBundle:Information')->findOpenedDsc($max);

		return $this->render('DigsInformationBundle:Information:toppanel.html.twig', array(
            'entities' => $entities,
        ));
    }

	public function fileAction(Request $request)
	{
		if (false === $this->get('security.context')->isGranted('ROLE_INFORMATION'))
		{
			throw new AccessDeniedException();
		}

		$prefix = 'information';
        $em = $this->getDoctrine()->getManager();

		return $this->get('digs_file.controller')->indexAction(
			$em->getRepository('DigsFileBundle:File')->findAllByMemberQuery($prefix),
			$request->query->get('page', 1),
			12,
			'information_file_show',
			$prefix,
			'information_file_new',
			'information_file'
			);
	}

	public function newFileAction(Request $request)
	{
		if (false === $this->get('security.context')->isGranted('ROLE_INFORMATION'))
		{
			throw new AccessDeniedException();
		}

		return $this->get('digs_file.controller')->newAction(
			$request,
			'information_file_new',
			$this->container->getParameter('upload_dir') . DIRECTORY_SEPARATOR,
			'information'
			);
	}

	public function showFileAction($file, $title)
	{
		return $this->get('digs_file.controller')->showAction(
			$this->container->getParameter('upload_dir') . DIRECTORY_SEPARATOR, 'information', $file);
	}
	
	public function photoAction(Request $request)
	{
		if (false === $this->get('security.context')->isGranted('ROLE_INFORMATION'))
		{
			throw new AccessDeniedException();
		}

		$prefix = 'information';
        $em = $this->getDoctrine()->getManager();

		return $this->get('digs_photo.controller')->indexAction(
			$em->getRepository('DigsPhotoBundle:Photo')->findAllByMemberOrderByDescQuery($prefix),
			$request->query->get('page', 1),
			12,
			'information_photo_show',
			$prefix,
			'information_photo_new',
			'information_photo',
			'information_photo_thumbnail_show'
			);
	}

	public function newPhotoAction(Request $request)
	{
		if (false === $this->get('security.context')->isGranted('ROLE_INFORMATION'))
		{
			throw new AccessDeniedException();
		}

		return $this->get('digs_photo.controller')->newAction(
			$request,
			'information_photo_new',
			$this->container->getParameter('upload_dir') . DIRECTORY_SEPARATOR,
			'information'
			);
	}

	public function showPhotoAction($file)
	{
		return $this->get('digs_photo.controller')->showAction(
			$this->container->getParameter('upload_dir') . DIRECTORY_SEPARATOR,
			'information',
			$file);
	}

	public function showThumbnailAction($file)
	{
		return $this->get('digs_photo.controller')->showThumbnailAction(
			$this->container->getParameter('upload_dir') . DIRECTORY_SEPARATOR,
			'information',
			$file);
	}
}
