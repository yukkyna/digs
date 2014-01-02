<?php

namespace Digs\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;

use Digs\CoreBundle\Entity\Invite;
use Digs\CoreBundle\Form\InviteType;

/**
 * Invite controller.
 *
 */
class InviteController extends Controller implements AdminController
{
    /**
     * Displays a form to create a new Invite entity.
     *
     */
    public function newAction(Request $request)
    {
        $entity = new Invite();
        $form = $this->createCreateForm($entity);
		if ($request->isMethod('POST'))
		{
			$form->handleRequest($request);

			if ($form->isValid()) {
				
				$groups = $form['group']->getData();
				if ($groups && count($groups) > 0)
				{
					$em = $this->getDoctrine()->getManager();
					if (!$em->getRepository('DigsCoreBundle:Member')->exist($entity->getEmail()))
					{
						$groupIds = "";
						$b = false;
						foreach ($groups as $g)
						{
							if ($b)
							{
								$groupIds .= ',';								
							}
							$groupIds .= $g->getId();
							$b = true;
						}

						$ticket   = md5(uniqid(null, true));
						$password = md5(uniqid(null, true));
						$entity->setTicket($ticket);
						$entity->setPassword($password);
						$entity->setCategoryIds($groupIds);
						$entity->setCreatedAt(new \DateTime());

						$em->persist($entity);
						$em->flush();

						$message = \Swift_Message::newInstance()
							->setSubject('digsへようこそ')
							->setFrom($this->container->getParameter('mailer_from'))
							->setTo($entity->getEmail())
							->setBody(
								$this->renderView('DigsCoreBundle:Invite:email.txt.twig', array(
									'ticket' => $entity->getTicket(),
									'password' => $entity->getPassword(),
									)
								)
							);
						$this->get('mailer')->send($message);

						return $this->redirect($this->generateUrl('invite_invited'));
					}
					else {
						$form['email']->addError(new FormError('既に登録されています。'));
					}
				}
				else
				{
					$form['group']->addError(new FormError('グループを選択してください。'));
				}
			}
		}
        return $this->render('DigsCoreBundle:Invite:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Invite entity.
    *
    * @param Invite $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Invite $entity)
    {
        $form = $this->createForm(new InviteType(), $entity, array(
            'action' => $this->generateUrl('invite_new'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Finds and displays a Invite entity.
     *
     */
    public function invitedAction()
    {
		return $this->render('DigsCoreBundle:Invite:invited.html.twig', array());
    }
}
