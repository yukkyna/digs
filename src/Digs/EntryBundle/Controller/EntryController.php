<?php

namespace Digs\EntryBundle\Controller;

use Digs\EntryBundle\Entity\Entry;
use Digs\EntryBundle\Entity\EntryAlert;
use Digs\EntryBundle\Entity\EntryComment;
use Digs\EntryBundle\Entity\EntryTag;
use Digs\EntryBundle\Form\EntryCommentType;
use Digs\EntryBundle\Form\EntryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

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
        $form = $this->createSearchForm();
		$form->handleRequest($request);
		
        $entities = $this->get('knp_paginator')->paginate(
			$em->getRepository('DigsEntryBundle:Entry')->findOpenedDscQueryBuilder(
					$form['search']->getData(),
					$request->query->get('tag'),
					$request->query->get('profile')
				)->getQuery(),
			$request->query->get('page', 1),
			18
			);

		return $this->render('DigsEntryBundle:Entry:index.html.twig', array(
            'entities' => $entities,
			'form' => $form->createView(),
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

    public function alertAction()
    {
		$entities = $this->getDoctrine()->getManager()
			->getRepository('DigsEntryBundle:EntryAlert')->findByMember($this->getUser()->getId());

		return $this->render('DigsEntryBundle:Entry:alert.html.twig', array(
            'entities' => $entities,
        ));
    }

    public function profilePanelEntryAction($member, $max = 10)
    {
		$entities = $this->getDoctrine()->getManager()
			->getRepository('DigsEntryBundle:Entry')->findOpenedByMemberDsc($member, $max);

		return $this->render('DigsEntryBundle:Entry:profilepanel.html.twig', array(
            'entities' => $entities,
        ));
    }
	
    public function profilePanelCommentAction($member, $max = 10)
    {
		$entities = $this->getDoctrine()->getManager()
			->getRepository('DigsEntryBundle:EntryComment')->findOpenedByMemberDsc($member, $max);

		return $this->render('DigsEntryBundle:Entry:profile_panel_comment.html.twig', array(
            'entities' => $entities,
        ));
    }

    public function profilePanelTagAction($member)
    {
		$entities = $this->getDoctrine()->getManager()
			->getRepository('DigsEntryBundle:EntryTag')->findOpenedEntryTagByMember($member);

		return $this->render('DigsEntryBundle:Entry:profilepanel_tag.html.twig', array(
            'entities' => $entities,
        ));
    }

	private function setTagList(Entry $entity, $taglist)
	{
		$em = $this->getDoctrine()->getManager();

		if ($entity->getTags())
		{
			$entity->getTags()->clear();
		}

		$isDuplicate = array();
		$tagNames = explode(',', $taglist);

		foreach ($tagNames as $name)
		{
			$str = preg_replace('/^[ 　]+/u', '', $name);
			$str = preg_replace('/[ 　]+$/u', '', $str);
			if (mb_strlen($str, 'UTF-8') === 0 || mb_strlen($str, 'UTF-8') === FALSE)
			{
				continue;
			}
			if (isset($isDuplicate[$str]))
			{
				continue;
			}

			for ($i = 0; $i < 2; $i ++)
			{
				$tag = $em->getRepository('DigsEntryBundle:EntryTag')->findOneByNameOrderASC($str);
				if (!$tag)
				{
					$tag = new EntryTag();
					$tag->setName($str);
					$em->persist($tag);
					$em->flush();
//
//					$tag = new EntryTag();
//					$tag->setName($str);
//					$em->persist($tag);
//					$em->flush();
				}
				else
				{
					break;
				}
			}
			$entity->addTag($tag);
			$isDuplicate[$str] = true;
		}
	}

	/**
     * Displays a form to create a new Entry entity.
     *
     */
    public function newAction(Request $request)
    {
		$entity = new Entry();
		$form = $this->createCreateForm($entity);

		if ($request->isMethod('POST'))
		{
			$form->handleRequest($request);
			if ($form->isValid()) {

				$this->setTagList($entity, $form['taglist']->getData());

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
    * @return Form The form
    */
    private function createCreateForm(Entry $entity)
    {
        $form = $this->createForm(new EntryType(), $entity, array(
            'action' => $this->generateUrl('entry_new'),
            'method' => 'POST',
        ));
		$form->add('taglist', 'text', array(
			'mapped' => false,
			'required' => false,
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

		if ($entity->getMember()->getId() === $this->getUser()->getId())
		{
			$em->getRepository('DigsEntryBundle:EntryAlert')->deleteByMemberAndEntry($this->getUser()->getId(), $entity->getId());
		}

		if ($request->isMethod('POST'))
		{
	        $commentForm->handleRequest($request);
			if ($commentForm->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($comment);
				$em->flush();

				if ($entity->getMember()->getId() !== $this->getUser()->getId())
				{
					$alert = new EntryAlert();
					$alert->setEntry($entity);
					$alert->setMember($entity->getMember());
					$em->persist($alert);
					$em->flush();
				}
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

				$this->setTagList($entity, $editForm['taglist']->getData());

		        $em = $this->getDoctrine()->getManager();
				$em->persist($entity);
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
    * @return Form The form
    */
    private function createEditForm(Entry $entity)
    {
        $form = $this->createForm(new EntryType(), $entity, array(
            'action' => $this->generateUrl('entry_edit', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
		$form->add('taglist', 'text', array(
			'mapped' => false,
			'required' => false,
		));
		$taglist = '';
		$flag = false;
		foreach ($entity->getTags() as $tag)
		{
			if ($flag)
			{
				$taglist .= ', ';
			}
			$taglist .= $tag->getName();
			$flag = true;
		}
		$form['taglist']->setData($taglist);
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
			if ($entity->getMember()->getId() !== $this->getUser()->getId())
			{
                throw $this->createNotFoundException('Unable to delete Entry entity.');
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
     * @return Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('entry_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    private function createSearchForm()
    {
        $form = $this->createFormBuilder(null, array('csrf_protection' => false))
            ->setAction($this->generateUrl('entry'))
            ->setMethod('GET')
            ->getForm()
			->add('search', 'text', array(
			'required' => false,
			))
        ;
		return $form;
    }
}
