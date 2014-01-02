<?php

namespace Digs\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Digs\CoreBundle\Entity\Restore;
use Digs\CoreBundle\Form\RestoreType;
use Symfony\Component\Form\FormError;

/**
 * Restore controller.
 *
 */
class RestoreController extends Controller
{
    /**
     * Creates a new Restore entity.
     *
     */
    public function createAction(Request $request)
    {
        $form = $this->createCreateForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
			$data = $form->getData();
            $em = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('DigsCoreBundle:Member')->findOneBy(array(
				'email' => $data['email'],
				'active' => true
			));
			if ($entity)
			{
				$rest = new Restore();
				$rest->setMember($entity);
				$rest->setPassword(md5(uniqid(null, true)));
				$rest->setTicket(md5(uniqid(null, true)));
				$rest->setCreatedAt(new \DateTime());

				$em = $this->getDoctrine()->getManager();
				$em->persist($rest);
				$em->flush();

				$message = \Swift_Message::newInstance()
					->setSubject('パスワード変更のお知らせ')
					->setFrom($this->container->getParameter('mailer_from'))
					->setTo($data['email'])
					->setBody(
						$this->renderView('DigsCoreBundle:Restore:email.txt.twig', array(
							'ticket' => $rest->getTicket(),
							'password' => $rest->getPassword(),
							)
						)
					);
				$this->get('mailer')->send($message);
			}
        }
        return $this->redirect($this->generateUrl('restore_send'));
    }

    /**
    * Creates a form to create a Restore entity.
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('restore_create'))
            ->setMethod('POST')
            ->add('email', 'text')
            ->getForm()
        ;
    }

    /**
     * Displays a form to create a new Restore entity.
     *
     */
    public function newAction()
    {
        $entity = new Restore();
        $form   = $this->createCreateForm($entity);

        return $this->render('DigsCoreBundle:Restore:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

	protected function createConfirmForm($id)
	{
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('restore_restore', array(
				'id' => $id
			)))
            ->setMethod('POST')
            ->add('email', 'text')
            ->add('password', 'password')
            ->getForm()
        ;
	}
	
    public function confirmAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DigsCoreBundle:Restore')->findByTicketAndActiveMember($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Restore entity.');
        }

        $form = $this->createConfirmForm($id);

		return $this->render('DigsCoreBundle:Restore:confirm.html.twig', array(
			'entity' => $entity,
			'form'   => $form->createView())
			);
    }

    public function restoreAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DigsCoreBundle:Restore')->findByTicketAndActiveMember($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Restore entity.');
        }

        $form = $this->createConfirmForm($id);
		$form->handleRequest($request);
        if ($form->isValid()) {
			$data = $form->getData();
			$user = $entity->getMember();

			if ($user->getEmail() === $data['email'] && $entity->getPassword() === $data['password'])
			{
				$factory = $this->get('security.encoder_factory');
				$encoder = $factory->getEncoder($user);
				$password = $encoder->encodePassword($data['password'], $user->getSalt());
				$user->setPassword($password);
				$em->persist($user);
				$em->flush();
	            return $this->redirect($this->generateUrl('restore_success'));
			}
		}

		$form->addError(new FormError('メールアドレスかパスワードに誤りがあります'));
        return $this->render('DigsCoreBundle:Restore:confirm.html.twig', array(
            'entity'      => $entity,
			'form' => $form->createView()
			));
    }

	public function sendAction()
	{
        return $this->render('DigsCoreBundle:Restore:send.html.twig', array());
	}

	public function successAction()
	{
        return $this->render('DigsCoreBundle:Restore:success.html.twig', array());
	}
}
