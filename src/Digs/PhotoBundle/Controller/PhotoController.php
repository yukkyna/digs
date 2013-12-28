<?php

namespace Digs\PhotoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Digs\PhotoBundle\Entity\Photo;
use Digs\PhotoBundle\Form\PhotoType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Photo controller.
 *
 */
class PhotoController extends Controller
{

    /**
     * Lists all Photo entities.
     *
     */
    public function indexAction(Request $request)
    {
		
        $em = $this->getDoctrine()->getManager();

        $entities = $this->get('knp_paginator')->paginate(
			$em->getRepository('DigsPhotoBundle:Photo')->findAllByMemberOrderByDescQuery($this->getUser()->getId()),
			$request->query->get('page', 1),
			16
			);

        return $this->render('DigsPhotoBundle:Photo:index.html.twig', array(
            'entities' => $entities,
			'prefix' => $this->getUser()->getId(),
        ));
    }
	
    public function selectAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $this->get('knp_paginator')->paginate(
			$em->getRepository('DigsPhotoBundle:Photo')->findAllByMemberOrderByDescQuery($this->getUser()->getId()),
			$request->query->get('page', 1),
			12
			);

        return $this->render('DigsPhotoBundle:Photo:select.html.twig', array(
            'entities' => $entities,
			'prefix' => $this->getUser()->getId(),
        ));
    }

    /**
     * Displays a form to create a new Photo entity.
     *
     */
    public function newAction(Request $request)
    {
        $entity = new Photo();
        $form   = $this->createCreateForm($entity);

		if ($request->isMethod('POST'))
		{
			$form->handleRequest($request);

			if ($form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($entity);
				$em->flush();

				return $this->redirect($this->generateUrl('photo_show', array('id' => $entity->getId())));
			}
		}
		
        return $this->render('DigsPhotoBundle:Photo:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Photo entity.
    *
    * @param Photo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Photo $entity)
    {
        $form = $this->createForm(new PhotoType(), $entity, array(
            'action' => $this->generateUrl('photo_new'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

	/**
     * Finds and displays a Photo entity.
     *
     */
    public function showAction(Request $request, $prefix, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DigsPhotoBundle:Photo')->findMemberPhoto($prefix, $id);

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
			'Content-Type'   => 'image/jpeg',
//			'Content-Length' => filesize($path),
			'X-Sendfile'     => $this->container->getParameter('upload_dir') . DIRECTORY_SEPARATOR
			. $entity->getMember()->getId() . DIRECTORY_SEPARATOR . 'photo' . DIRECTORY_SEPARATOR . $entity->getFile() . '.jpg',
		));
		$response->setStatusCode(200);
//		$response =  new Response($image, 200);
		$response->setLastModified($entity->getCreatedAt());
        return $response;
//		
//		
//        return $this->render('DigsPhotoBundle:Photo:show.html.twig', array(
//            'entity'      => $entity,
//			));
    }

    public function showThumbnailAction(Request $request, $prefix, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DigsPhotoBundle:Photo')->findMemberPhoto($prefix, $id);

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
			'Content-Type'   => 'image/jpeg',
//			'Content-Length' => filesize($path),
			'X-Sendfile'     => $this->container->getParameter('upload_dir') . DIRECTORY_SEPARATOR
			. $entity->getMember()->getId() . DIRECTORY_SEPARATOR . 'photo' . DIRECTORY_SEPARATOR . 't_' .$entity->getFile() . '.jpg',
		));
		$response->setStatusCode(200);
//		$response =  new Response($image, 200);
		$response->setLastModified($entity->getCreatedAt());
        return $response;
//		
//		
//        return $this->render('DigsPhotoBundle:Photo:show.html.twig', array(
//            'entity'      => $entity,
//			));
    }
//
//	/**
//     * Displays a form to edit an existing Photo entity.
//     *
//     */
//    public function editAction($id)
//    {
//        $em = $this->getDoctrine()->getManager();
//
//        $entity = $em->getRepository('DigsPhotoBundle:Photo')->find($id);
//
//        if (!$entity) {
//            throw $this->createNotFoundException('Unable to find Photo entity.');
//        }
//
//        $editForm = $this->createEditForm($entity);
//        $deleteForm = $this->createDeleteForm($id);
//
//        return $this->render('DigsPhotoBundle:Photo:edit.html.twig', array(
//            'entity'      => $entity,
//            'edit_form'   => $editForm->createView(),
//            'delete_form' => $deleteForm->createView(),
//        ));
//    }
//
//    /**
//    * Creates a form to edit a Photo entity.
//    *
//    * @param Photo $entity The entity
//    *
//    * @return \Symfony\Component\Form\Form The form
//    */
//    private function createEditForm(Photo $entity)
//    {
//        $form = $this->createForm(new PhotoType(), $entity, array(
//            'action' => $this->generateUrl('photo_update', array('id' => $entity->getId())),
//            'method' => 'PUT',
//        ));
//
//        $form->add('submit', 'submit', array('label' => 'Update'));
//
//        return $form;
//    }
//    /**
//     * Edits an existing Photo entity.
//     *
//     */
//    public function updateAction(Request $request, $id)
//    {
//        $em = $this->getDoctrine()->getManager();
//
//        $entity = $em->getRepository('DigsPhotoBundle:Photo')->find($id);
//
//        if (!$entity) {
//            throw $this->createNotFoundException('Unable to find Photo entity.');
//        }
//
//        $deleteForm = $this->createDeleteForm($id);
//        $editForm = $this->createEditForm($entity);
//        $editForm->handleRequest($request);
//
//        if ($editForm->isValid()) {
//            $em->flush();
//
//            return $this->redirect($this->generateUrl('photo_edit', array('id' => $id)));
//        }
//
//        return $this->render('DigsPhotoBundle:Photo:edit.html.twig', array(
//            'entity'      => $entity,
//            'edit_form'   => $editForm->createView(),
//            'delete_form' => $deleteForm->createView(),
//        ));
//    }
    /**
     * Deletes a Photo entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DigsPhotoBundle:Photo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Photo entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('photo'));
    }

    /**
     * Creates a form to delete a Photo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('photo_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
