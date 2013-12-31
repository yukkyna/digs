<?php

namespace Digs\FileBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Digs\FileBundle\Entity\File;
use Digs\FileBundle\Form\FileType;

/**
 * File controller.
 *
 */
class FileController extends Controller
{

    /**
     * Lists all File entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $this->get('knp_paginator')->paginate(
			$em->getRepository('DigsFileBundle:File')->findAllByMemberQuery($this->getUser()->getId()),
			$request->query->get('page', 1),
			12
			);
		$uploadForm = $this->createCreateForm();

        return $this->render('DigsFileBundle:File:index.html.twig', array(
            'entities' => $entities,
			'prefix' => $this->getUser()->getId(),
			'upload_form' => $uploadForm->createView()
        ));
    }

    /**
    * Creates a form to create a File entity.
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm()
    {
		return $this->createFormBuilder()
			->setAction($this->generateUrl('file_new'))
			->setMethod('POST')
            ->add('file', 'file')
            ->getForm();
//
//		$form = $this->createForm(new FileType(), $entity, array(
//            'action' => $this->generateUrl('file_create'),
//            'method' => 'POST',
//        ));
//
//        $form->add('submit', 'submit', array('label' => 'Create'));
//
//        return $form;
    }

    /**
     * Displays a form to create a new File entity.
     *
     */
    public function newAction(Request $request)
    {
		$form = $this->createCreateForm();
		try
		{
			if ($request->isMethod('POST'))
			{
				$form->handleRequest($request);
				if ($form->isValid()) {
					
					$dir = $this->container->getParameter('upload_dir') . DIRECTORY_SEPARATOR . $this->getUser()->getId() . DIRECTORY_SEPARATOR . 'file' . DIRECTORY_SEPARATOR;
					
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
					$file->move($dir, $newname);
					
					$entity = new File();
					$entity->setTitle($file->getClientOriginalName());
					$entity->setFile($newname);
					$entity->setMember($this->getUser());
					$entity->setTextData("");

					$em = $this->getDoctrine()->getManager();
					$em->persist($entity);
					$em->flush();
				}
			}
		}
		catch (FileException $e)
		{
			$form->addError(new FormError('ファイルサイズが大きすぎます。'));
		}

        return $this->render('DigsFileBundle:File:new.html.twig', array(
            'upload_form'   => $form->createView(),
        ));
    }

	/**
     * Finds and displays a File entity.
     *
     */
    public function showAction($prefix, $file, $title)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DigsFileBundle:File')->findMemberFile($prefix, $file);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

		$response = new Response();
//		$response->setLastModified($entity->getCreatedAt()->);
//		if ($response->isNotModified($request))
//		{
//			return $response;
//		}
		$response->headers->add(array(
			'Content-Type'   => 'application/octet-stream',
//			'Content-Length' => filesize($path),
			'X-Sendfile'     => $this->container->getParameter('upload_dir') . DIRECTORY_SEPARATOR
			. $entity->getMember()->getId() . DIRECTORY_SEPARATOR . 'file' . DIRECTORY_SEPARATOR . $entity->getFile(),
		));
		$response->setStatusCode(200);
//		$response =  new Response($image, 200);
		$response->setLastModified($entity->getCreatedAt());
        return $response;
//
//		
//		
//		$em = $this->getDoctrine()->getManager();
//
//        $entity = $em->getRepository('DigsFileBundle:File')->find($id);
//
//        if (!$entity) {
//            throw $this->createNotFoundException('Unable to find File entity.');
//        }
//
//        $deleteForm = $this->createDeleteForm($id);
//
//        return $this->render('DigsFileBundle:File:show.html.twig', array(
//            'entity'      => $entity,
//            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing File entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DigsFileBundle:File')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find File entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('DigsFileBundle:File:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a File entity.
    *
    * @param File $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(File $entity)
    {
        $form = $this->createForm(new FileType(), $entity, array(
            'action' => $this->generateUrl('file_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing File entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DigsFileBundle:File')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find File entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('file_edit', array('id' => $id)));
        }

        return $this->render('DigsFileBundle:File:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a File entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DigsFileBundle:File')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find File entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('file'));
    }

    /**
     * Creates a form to delete a File entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('file_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
