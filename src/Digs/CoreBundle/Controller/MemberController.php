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

    /**
     * グループへの追加・削除
     * @param type $id Member ID
     * @return type
     * @throws type
     */
    public function groupAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $groups = $em->getRepository('DigsCoreBundle:MemberGroup')->findAll();
        if (!$groups) {
            throw $this->createNotFoundException('Unable to find Group entity.');
        }
        
        $member = $em->getRepository('DigsCoreBundle:Member')->findJoinGroups($id);
        if (!$member) {
            throw $this->createNotFoundException('Unable to find Member entity.');
        }
        
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('member_group', array('id' => $id)))
            ->setMethod('PUT')
            ->getForm()
        ;

		$form->add('groups', 'entity', array(
				'class'    => 'DigsCoreBundle:MemberGroup',
				'property' => 'name',
				'multiple' => true,
				'expanded' => true,
				'mapped'   => false,
//                'data'     => $member->getGroups()
			));

		if ($request->isMethod('PUT'))
		{
			$form->handleRequest($request);
			if ($form->isValid()) {
                $oldGroups = $member->getGroups();
                foreach ($oldGroups as $og) {
                    $member->removeGroup($og);
                }
                $newGroups = $form['groups']->getData();
//                echo count($newGroups);
                foreach ($newGroups as $ng) {

                    echo $ng->getId() . ' ';
                    
                    $member->addGroup($ng);
                }
//                die;
				$em->persist($member);
				$em->flush();
				return $this->redirect($this->generateUrl('member'));
			}
		} else {
            $form['groups']->setData($member->getGroups());
        }
        
        return $this->render('DigsCoreBundle:Member:group.html.twig', array(
            'entity' => $member,
            'form'   => $form->createView()
        ));
    }
}
