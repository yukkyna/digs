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

        $entities = $em->getRepository('DigsCoreBundle:Member')->findJoinGroupAndRole();

        $form = $this->createActivityForm();

        return $this->render('DigsCoreBundle:Member:index.html.twig', array(
            'entities' => $entities,
            'activity_form' => $form->createView()
        ));
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
    
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DigsCoreBundle:Member')->findJoinGroupAndRole($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Member entity.');
        }
        return $this->render('DigsCoreBundle:Member:show.html.twig', array(
            'entity' => $entity,
        ));
    }
}
