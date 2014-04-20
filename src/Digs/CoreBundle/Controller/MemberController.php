<?php

namespace Digs\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Digs\CoreBundle\Entity\Member;
use Digs\CoreBundle\Form\MemberType;

/**
 * Member controller.
 *
 */
class MemberController extends Controller implements AdminController
{

    /**
     * Lists all Member entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DigsCoreBundle:Member')->findAllJoinProfile();

        $form = $this->createActivityForm();

        return $this->render('DigsCoreBundle:Member:index.html.twig', array(
            'entities' => $entities,
            'activity_form' => $form->createView()
        ));
    }
    /**
     * Creates a new Member entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Member();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('member_show', array('id' => $entity->getId())));
        }

        return $this->render('DigsCoreBundle:Member:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Member entity.
    *
    * @param Member $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Member $entity)
    {
        $form = $this->createForm(new MemberType(), $entity, array(
            'action' => $this->generateUrl('member_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Member entity.
     *
     */
    public function newAction()
    {
        $entity = new Member();
        $form   = $this->createCreateForm($entity);

        return $this->render('DigsCoreBundle:Member:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Member entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DigsCoreBundle:Member')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Member entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('DigsCoreBundle:Member:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    public function activityAction(Request $request)
    {
		$form = $this->createActivityForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
			$id = $form['mid']->getData();
			$entity = $em->getRepository('DigsCoreBundle:Member')->find($id);
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Member entity.');
            }
            
			$status = $form['status']->getData();
            if ($status === 'true')
            {
                $entity->setActive(true);
                
            }
            else if ($status === 'false')
            {
                $entity->setActive(false);
            }
            else
            {
				throw $this->createNotFoundException('Invalid status.');
            }
			$em->persist($entity);
			$em->flush();
        }

        return $this->redirect($this->generateUrl('member'));
    }

    /**
     * Creates a form to delete a Member entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createActivityForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('member_activity'))
            ->setMethod('PUT')
            ->getForm()
            ->add('mid', 'hidden')
            ->add('status', 'hidden')
        ;
    }
}
