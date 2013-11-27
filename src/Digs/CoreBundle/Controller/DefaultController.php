<?php

namespace Digs\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
//		var_dump($this->get('digs_core.navigation_manager'));die;
        return $this->render('DigsCoreBundle:Default:index.html.twig', array(
			
		));
    }
}
