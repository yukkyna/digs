<?php

namespace Digs\FileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('DigsFileBundle:Default:index.html.twig', array('name' => $name));
    }
}
