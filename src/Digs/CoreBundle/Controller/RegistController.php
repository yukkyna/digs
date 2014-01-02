<?php

namespace Digs\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints\Length;

use Digs\CoreBundle\Entity\Invite;
use Digs\CoreBundle\Form\InviteType;
use Digs\CoreBundle\Form\ProfileType;

/**
 * Regist controller.
 *
 */
class RegistController extends Controller
{
	public function indexAction(Request $request, $id)
	{
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DigsCoreBundle:Invite')->findByActiveTicket($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Profile entity.');
        }

		$form = $this->createFormBuilder()
            ->setAction($this->generateUrl('regist', array(
				'id' => $id
			)))
            ->setMethod('POST')
            ->add('email', 'text')
            ->add('password', 'password')
			->add('first_name', 'text')
			->add('last_name', 'text')
            ->add('newPassword', 'repeated', array(
				'type' => 'password',
				'invalid_message' => 'パスワードが一致しません。',
				'constraints' => array(
					new Length(array(
						'min' => 6
						))
				)
			))
			->add('profile', new ProfileType())
            ->getForm()
        ;
		$form['profile']->remove('entryLead');
		$form['profile']->remove('profileImage');

		if ($request->isMethod('POST'))
		{
			$form->handleRequest($request);

			if ($form->isValid()) {
				if ($form['email']->getData() == $entity->getEmail() && $form['password']->getData() == $entity->getPassword())
				{
					$em = $this->getDoctrine()->getManager();
					
					$member = new \Digs\CoreBundle\Entity\Member();
					$member->setEmail($form['email']->getData());
					$member->setFirstName($form['first_name']->getData());
					$member->setLastName($form['last_name']->getData());
					$member->setPassword('0');	// dummy;
					$member->setActive(true);
					$em->persist($member);
					$em->flush();

					$pf = $form['profile'];
					$profile = new \Digs\CoreBundle\Entity\Profile();
					$profile->setNickname($pf['nickname']->getData());
					$profile->setMessage($pf['message']->getData());
					$profile->setEntryNum(0);
					$profile->setUpdatedAt(new \DateTime());
					$profile->setMember($member);
					$em->persist($profile);
					$em->flush();

					$encoder = $this->get('security.encoder_factory')->getEncoder($member);
					$password = $encoder->encodePassword($form['newPassword']->getData(), $member->getSalt());
					$member->setPassword($password);
					$member->setProfile($profile);
					foreach ($em->getRepository('DigsCoreBundle:MemberGroup')->findAllInIds(explode(',', $entity->getCategoryIds())) as $group)
					{
						$member->addGroup($group);
					}
					
					$member->addRole($em->getRepository('DigsCoreBundle:Role')->findByRole('ROLE_USER'));

					$em->persist($member);
					$em->flush();

					$dir = $this->container->getParameter('upload_dir') . DIRECTORY_SEPARATOR . $member->getId() . DIRECTORY_SEPARATOR;
					mkdir($dir);
					mkdir($dir . 'photo');
					mkdir($dir . 'file');
					mkdir($dir . 'movie');
					copy(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'profile.png',
						$dir . 'profile.png');

					return $this->redirect($this->generateUrl('regist_success'));
				}
				else {
					$form->addError(new FormError('メールアドレス もしくは 登録IDが間違っています'));
				}
			}
		}

		return $this->render('DigsCoreBundle:Regist:index.html.twig', array(
			'form' => $form->createView()
        ));
	}

    public function successAction()
    {
		return $this->render('DigsCoreBundle:Regist:success.html.twig', array());
    }
}
