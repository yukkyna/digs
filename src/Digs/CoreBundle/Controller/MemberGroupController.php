<?php

namespace Digs\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Digs\CoreBundle\Entity\MemberGroup;
use Digs\CoreBundle\Form\MemberGroupType;

/**
 * MemberGroup controller.
 *
 */
class MemberGroupController extends Controller implements AdminController
{

    /**
     * Lists all MemberGroup entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DigsCoreBundle:MemberGroup')->findAllJoinMembers();

        return $this->render('DigsCoreBundle:MemberGroup:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new MemberGroup entity.
     *
     */
    public function newAction(Request $request)
    {
        $entity = new MemberGroup();
        $form = $this->createCreateForm($entity);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('membergroup'));
            }
            
        }

        return $this->render('DigsCoreBundle:MemberGroup:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a MemberGroup entity.
    *
    * @param MemberGroup $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(MemberGroup $entity)
    {
        $form = $this->createForm(new MemberGroupType(), $entity, array(
            'action' => $this->generateUrl('membergroup_new'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Finds and displays a MemberGroup entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DigsCoreBundle:MemberGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MemberGroup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('DigsCoreBundle:MemberGroup:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
    * Creates a form to edit a MemberGroup entity.
    *
    * @param MemberGroup $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(MemberGroup $entity)
    {
        $form = $this->createForm(new MemberGroupType(), $entity, array(
            'action' => $this->generateUrl('membergroup_edit', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing MemberGroup entity.
     *
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DigsCoreBundle:MemberGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MemberGroup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        if ($request->isMethod('PUT'))
        {
            $editForm->handleRequest($request);

            if ($editForm->isValid()) {
                $em->flush();

                return $this->redirect($this->generateUrl('membergroup', array('id' => $id)));
            }
        }

        return $this->render('DigsCoreBundle:MemberGroup:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a MemberGroup entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DigsCoreBundle:MemberGroup')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find MemberGroup entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('membergroup'));
    }

    /**
     * Creates a form to delete a MemberGroup entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('membergroup_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
