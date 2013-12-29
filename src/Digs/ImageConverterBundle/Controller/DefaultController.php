<?php

namespace Digs\ImageConverterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('DigsImageConverterBundle:Default:index.html.twig', array('name' => $name));
    }
}
