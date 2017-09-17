<?php

namespace Creavo\OptionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {


        return $this->render('CreavoOptionBundle:Default:index.html.twig');
    }
}
