<?php

namespace Digs\InformationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('DigsInformationBundle:Default:index.html.twig', array('name' => $name));
    }
}
