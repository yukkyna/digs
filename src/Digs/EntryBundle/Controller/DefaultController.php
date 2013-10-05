<?php

namespace Digs\EntryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('DigsEntryBundle:Default:index.html.twig', array('name' => $name));
    }
}
