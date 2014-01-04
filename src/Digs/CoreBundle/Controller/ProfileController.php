<?php

namespace Digs\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints\Length;

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
            'profile' => $entity
			));
    }

    /**
     * Displays a form to edit an existing Profile entity.
     *
     */
    public function editAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$id = $this->getUser()->getProfile()->getId();
			
        $entity = $em->getRepository('DigsCoreBundle:Profile')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Profile entity.');
        }

        $editForm = $this->createEditForm($entity);

		if ($request->isMethod('PUT'))
		{
			$editForm->handleRequest($request);

			if ($editForm->isValid()) {
				$ret = 0;
				$file = $editForm['profileImage']->getData();
				if ($file)
				{
					$dir = $this->container->getParameter('upload_dir') . DIRECTORY_SEPARATOR . $this->getUser()->getId() . DIRECTORY_SEPARATOR;
					$file->move($dir, 'profile.original');
					$im = $this->get('digs_image_converter.manager');
					$ret = $im->convert($dir . 'profile.original ' . '-quality 80 -resize 180x180 -gravity center -background #ff000000 -extent 180x180 ' . $dir . 'profile.png');
				}
				if ($ret == 0)
				{
					$entity->setUpdatedAt(new \DateTime());
					$em->flush();
					return $this->redirect($this->generateUrl('profile_show', array(
						'id' => $id
						)));
				}
				else {
					$editForm['profileImage']->addError(new FormError('対応していない画像です。'));
				}
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

        return $form;
    }

	public function showImageAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DigsCoreBundle:Profile')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Photo entity.');
        }
		
		$path = $this->container->getParameter('upload_dir') . DIRECTORY_SEPARATOR
			. $entity->getMember()->getId() . DIRECTORY_SEPARATOR . 'profile.png';
		ob_start();
			readfile($path);
			$image = ob_get_contents();
		ob_end_clean();

		$response = new Response($image, 200, array(
			'Content-Type'   => 'image/png',
			'Content-Length' => filesize($path),
		));
		$response->setLastModified($entity->getUpdatedAt());
        return $response;
    }
	
	public function editPasswordAction(Request $request)
	{
        $em = $this->getDoctrine()->getManager();
//		$id = $this->getUser()->getProfile()->getId();
//        $entity = $em->getRepository('DigsCoreBundle:Profile')->find($id);
//
//        if (!$entity) {
//            throw $this->createNotFoundException('Unable to find Profile entity.');
//        }

        $editForm = $this->createFormBuilder()
			->setAction($this->generateUrl('profile_password_edit'))
			->setMethod('PUT')
            ->add('currentPassword', 'password')
            ->add('newPassword', 'repeated', array(
				'type' => 'password',
				'invalid_message' => '新しいパスワードが一致しません。',
				'constraints' => array(
					new Length(array(
						'min' => 6
						))
				)
			))
            ->getForm();

		if ($request->isMethod('PUT'))
		{
			$editForm->handleRequest($request);

			if ($editForm->isValid()) {
				$encoder = $this->get('security.encoder_factory')->getEncoder($this->getUser());
				$encCurPass = $encoder->encodePassword($editForm['currentPassword']->getData(), $this->getUser()->getSalt());
				if ($this->getUser()->getPassword() === $encCurPass)
				{
					$newPass = $encoder->encodePassword($editForm['newPassword']->getData(), $this->getUser()->getSalt());
					
					$em = $this->getDoctrine()->getManager();
					$entity = $em->getRepository('DigsCoreBundle:Member')->find($this->getUser()->getId());
					$entity->setPassword($newPass);
					$em->persist($entity);
					$em->flush();
					return $this->redirect($this->generateUrl('profile_show', array(
						'id' => $this->getUser()->getProfile()->getId()
						)));
				}
				else
				{
					$editForm->addError(new FormError('パスワードが違います。'));
				}
			}
		}

        return $this->render('DigsCoreBundle:Profile:editPassword.html.twig', array(
            'edit_form'   => $editForm->createView(),
        ));
	}
	
	public function fileAction(Request $request)
	{
		$prefix = $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();

		return $this->get('digs_file.controller')->indexAction(
			$em->getRepository('DigsFileBundle:File')->findAllByMemberQuery($prefix),
			$request->query->get('page', 1),
			12,
			'profile_file_show',
			$prefix,
			'profile_file_new',
			'profile_file'
			);
	}
	
	public function newFileAction(Request $request)
	{
		return $this->get('digs_file.controller')->newAction(
			$request,
			'profile_file_new',
			$this->container->getParameter('upload_dir') . DIRECTORY_SEPARATOR,
			$this->getUser()->getId()
			);
	}

	public function showFileAction($prefix, $file, $title)
	{
        $em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('DigsCoreBundle:Member')->find($prefix);
        if (!$entity) {
            throw new NotFoundHttpException('Unable to find entity.');
        }
		if ($entity->getActive() == false)
		{
            throw new NotFoundHttpException('Member is not active.');
		}

		return $this->get('digs_file.controller')->showAction(
			$this->container->getParameter('upload_dir') . DIRECTORY_SEPARATOR, $entity->getId(), $file);
	}
	
	public function photoAction(Request $request)
	{
		$prefix = $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();

		return $this->get('digs_photo.controller')->indexAction(
			$em->getRepository('DigsPhotoBundle:Photo')->findAllByMemberOrderByDescQuery($prefix),
			$request->query->get('page', 1),
			12,
			'profile_photo_show',
			$prefix,
			'profile_photo_new',
			'profile_photo',
			'profile_photo_thumbnail_show'
			);
	}

	public function newPhotoAction(Request $request)
	{
		return $this->get('digs_photo.controller')->newAction(
			$request,
			'profile_photo_new',
			$this->container->getParameter('upload_dir') . DIRECTORY_SEPARATOR,
			$this->getUser()->getId()
			);
	}

	public function showPhotoAction(Request $request, $prefix, $file)
	{
        $em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('DigsCoreBundle:Member')->find($prefix);
        if (!$entity) {
            throw new NotFoundHttpException('Unable to find entity.');
        }
		if ($entity->getActive() == false)
		{
            throw new NotFoundHttpException('Member is not active.');
		}

		return $this->get('digs_photo.controller')->showAction(
			$request,
			$this->container->getParameter('upload_dir') . DIRECTORY_SEPARATOR,
			$entity->getId(),
			$file);
	}

	public function showThumbnailAction(Request $request, $prefix, $file)
	{
        $em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('DigsCoreBundle:Member')->find($prefix);
        if (!$entity) {
            throw new NotFoundHttpException('Unable to find entity.');
        }
		if ($entity->getActive() == false)
		{
            throw new NotFoundHttpException('Member is not active.');
		}

		return $this->get('digs_photo.controller')->showThumbnailAction(
			$request,
			$this->container->getParameter('upload_dir') . DIRECTORY_SEPARATOR,
			$entity->getId(),
			$file);
	}
}
