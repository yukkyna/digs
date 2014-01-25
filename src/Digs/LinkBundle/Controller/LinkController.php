<?php

namespace Digs\LinkBundle\Controller;

use Digs\CoreBundle\Controller\AdminController;
use Digs\LinkBundle\Entity\Link;
use Digs\LinkBundle\Form\LinkType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * Link controller.
 *
 */
class LinkController extends Controller
{

    /**
     * Lists all Link entities.
     *
     */
    public function indexAction()
    {
		if (false === $this->get('security.context')->isGranted('ROLE_ADMIN'))
		{
			throw new NotFoundHttpException();
		}

		$em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DigsLinkBundle:Link')->findAllOrderById();

        return $this->render('DigsLinkBundle:Link:index.html.twig', array(
            'entities' => $entities,
			'delete_form' => $this->createDeleteForm()->createView()
        ));
    }
    
    /**
     * Lists all Link entities.
     *
     */
    public function footerAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DigsLinkBundle:Link')->findAllOrderById();

        return $this->render('DigsLinkBundle:Link:footer.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Displays a form to create a new Link entity.
     *
     */
    public function newAction(Request $request)
    {
		if (false === $this->get('security.context')->isGranted('ROLE_ADMIN'))
		{
			throw new NotFoundHttpException();
		}

		$entity = new Link();
        $form   = $this->createCreateForm($entity);
		if ($request->isMethod('POST'))
		{
			$form->handleRequest($request);

			if ($form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($entity);
				$em->flush();

				return $this->redirect($this->generateUrl('link'));
			}
		}

        return $this->render('DigsLinkBundle:Link:edit.html.twig', array(
            'entity' => $entity,
            'edit_form'   => $form->createView(),
			'newlink' => true,
        ));
    }

	/**
    * Creates a form to create a Link entity.
    *
    * @param Link $entity The entity
    *
    * @return Form The form
    */
    private function createCreateForm(Link $entity)
    {
        $form = $this->createForm(new LinkType(), $entity, array(
            'action' => $this->generateUrl('link_new'),
            'method' => 'POST',
        ));

        return $form;
    }

	/**
     * Displays a form to edit an existing Link entity.
     *
     */
    public function editAction(Request $request, $id)
    {
		if (false === $this->get('security.context')->isGranted('ROLE_ADMIN'))
		{
			throw new NotFoundHttpException();
		}

		$em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DigsLinkBundle:Link')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Link entity.');
        }

        $editForm = $this->createEditForm($entity);
		
		if ($request->isMethod('PUT'))
		{
			$editForm->handleRequest($request);
			if ($editForm->isValid()) {
				$em->flush();

				return $this->redirect($this->generateUrl('link'));
			}
		}

        return $this->render('DigsLinkBundle:Link:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Link entity.
    *
    * @param Link $entity The entity
    *
    * @return Form The form
    */
    private function createEditForm(Link $entity)
    {
        $form = $this->createForm(new LinkType(), $entity, array(
            'action' => $this->generateUrl('link_edit', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }

	/**
     * Deletes a Link entity.
     *
     */
    public function deleteAction(Request $request)
    {
		if (false === $this->get('security.context')->isGranted('ROLE_ADMIN'))
		{
			throw new NotFoundHttpException();
		}

		$form = $this->createDeleteForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
			$ids = explode(',', $form['ids']->getData());
			foreach ($ids as $id)
			{
				$entity = $em->getRepository('DigsLinkBundle:Link')->find($id);
				if (!$entity) {
					throw $this->createNotFoundException('Unable to find Link entity.');
				}

				$em->remove($entity);
			}
			$em->flush();
        }

        return $this->redirect($this->generateUrl('link'));
    }

    /**
     * Creates a form to delete a Link entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Form The form
     */
    private function createDeleteForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('link_delete'))
            ->setMethod('DELETE')
            ->getForm()
			->add('ids', 'hidden')
        ;
    }
}
