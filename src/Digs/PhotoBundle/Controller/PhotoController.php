<?php

namespace Digs\PhotoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Digs\PhotoBundle\Entity\Photo;
use Digs\PhotoBundle\Form\PhotoType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Form\FormError;

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
		$uploadForm = $this->createCreateForm();

        return $this->render('DigsPhotoBundle:Photo:select.html.twig', array(
            'entities' => $entities,
			'prefix' => $this->getUser()->getId(),
			'upload_form' => $uploadForm->createView()
        ));
    }

    /**
     * Displays a form to create a new Photo entity.
     *
     */
    public function newAction(Request $request)
    {
//		$uploadForm = $this->createUploadForm();
//		if ($request->isMethod('POST'))
//		{
//			$uploadForm->handleRequest($request);
//
//			if ($uploadForm->isValid()) {
//				$em->flush();
//			}
//		}

		$form = $this->createCreateForm();
		try
		{
			if ($request->isMethod('POST'))
			{
				$form->handleRequest($request);
				if ($form->isValid()) {
					
					$dir = $this->container->getParameter('upload_dir') . DIRECTORY_SEPARATOR . $this->getUser()->getId() . DIRECTORY_SEPARATOR . 'photo' . DIRECTORY_SEPARATOR;
					
					// FIXME リトライしてもファイル名が重複し続けた場合
					$newname = '';
					for ($i = 0; $i < 32; $i ++)
					{
						$newname = md5(uniqid(null, true));
						if (!file_exists($dir . $newname))
						{
							break;
						}
					}

					$file = $form['file']->getData();
					$file->move($dir, $newname . '.original');
					
					$im = $this->get('digs_image_converter.manager');
					$ret = $im->convert('-quality 100 ' . $dir . $newname . '.original ' . $dir . $newname . '.jpg');
					
					if ($ret == 0)
					{
						$im->convert('-quality 60 -resize 300x300 ' . $dir . $newname . '.original ' . $dir . 't_' . $newname . '.jpg');
						$entity = new Photo();
						$entity->setTitle($file->getClientOriginalName());
						$entity->setFile($newname);
						$entity->setStatus(1);
						$entity->setMember($this->getUser());
						$em = $this->getDoctrine()->getManager();
						$em->persist($entity);
						$em->flush();
					}
					else {
						$form->addError(new FormError('対応していない画像です。'));
					}
				}
			}
		}
		catch (FileException $e)
		{
			$form->addError(new FormError('ファイルサイズが大きすぎます。'));
		}

		return $this->render('DigsPhotoBundle:Photo:uploaded.html.twig', array(
			'upload_form' => $form->createView()
		));
		
//		
//        return $this->render('DigsPhotoBundle:Photo:new.html.twig', array(
//            'entity' => $entity,
//            'form'   => $form->createView(),
//        ));
    }

    /**
    * Creates a form to create a Photo entity.
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm()
    {
		return $this->createFormBuilder()
			->setAction($this->generateUrl('photo_new'))
			->setMethod('POST')
            ->add('file', 'file')
            ->getForm();
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
