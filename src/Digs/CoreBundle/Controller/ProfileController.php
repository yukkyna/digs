<?php

namespace Digs\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Digs\CoreBundle\Entity\Profile;
use Digs\CoreBundle\Form\ProfileType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Profile controller.
 *
 */
class ProfileController extends Controller
{

    /**
     * Lists all Profile entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

		$entities = $this->get('knp_paginator')->paginate(
			$em->getRepository('DigsCoreBundle:Profile')->findAllActiveQueryBuilder()->getQuery(),
			$request->query->get('page', 1),
			18
			);

        return $this->render('DigsCoreBundle:Profile:index.html.twig', array(
            'entities' => $entities,
        ));
    }

	/**
     * Displays a form to create a new Profile entity.
     *
     */
    public function newAction(Request $request)
    {
        $entity = new Profile();
        $form   = $this->createCreateForm($entity);
		
		if ($request->isMethod('POST'))
		{
			$form->handleRequest($request);

			if ($form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($entity);
				$em->flush();

				return $this->redirect($this->generateUrl('profile_show', array('id' => $entity->getId())));
			}
		}
        return $this->render('DigsCoreBundle:Profile:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Profile entity.
    *
    * @param Profile $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Profile $entity)
    {
        $form = $this->createForm(new ProfileType(), $entity, array(
            'action' => $this->generateUrl('profile_new'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Finds and displays a Profile entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DigsCoreBundle:Profile')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Profile entity.');
        }

        return $this->render('DigsCoreBundle:Profile:show.html.twig', array(
            'entity'      => $entity
			));
    }

    /**
     * Displays a form to edit an existing Profile entity.
     *
     */
    public function editAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$id = $this->getUser()->getId();
			
        $entity = $em->getRepository('DigsCoreBundle:Profile')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Profile entity.');
        }

        $editForm = $this->createEditForm($entity);

		if ($request)
		{
			$editForm->handleRequest($request);

			if ($editForm->isValid()) {
				$em->flush();

				return $this->redirect($this->generateUrl('profile_edit'));
			}
		}

        return $this->render('DigsCoreBundle:Profile:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

	/**
    * Creates a form to edit a Profile entity.
    *
    * @param Profile $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Profile $entity)
    {
        $form = $this->createForm(new ProfileType(), $entity, array(
            'action' => $this->generateUrl('profile_edit'),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

	public function showImageAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DigsCoreBundle:Profile')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Photo entity.');
        }

		$response = new Response();
//		$response->setLastModified($entity->getCreatedAt()->);
//		if ($response->isNotModified($request))
//		{
//			return $response;
//		}

		$response->headers->add(array(
			'Content-Type'   => 'image/png',
//			'Content-Length' => filesize($path),
			'X-Sendfile'     => $this->container->getParameter('upload_dir') . DIRECTORY_SEPARATOR
			. $entity->getMember()->getId() . DIRECTORY_SEPARATOR . 'profile.png',
		));
		$response->setStatusCode(200);
//		$response =  new Response($image, 200);
		$response->setLastModified($entity->getUpdatedAt());
        return $response;
//		
//		
//        return $this->render('DigsPhotoBundle:Photo:show.html.twig', array(
//            'entity'      => $entity,
//			));
    }
}
