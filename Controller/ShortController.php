<?php
/**
 * Created by PhpStorm.
 * User: Сергей
 * Date: 16.02.2017
 * Time: 19:58
 */
namespace ShortLinkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\HttpFoundation\Request;



class ShortController extends Controller
{
    public function indexAction() {
        return $this->render('@short/default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ));

    }
}