<?php
/**
 * Created by PhpStorm.
 * User: Сергей
 * Date: 16.02.2017
 * Time: 19:58
 */
namespace ShortLinkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;




class ShortController extends Controller
{
    public function indexAction() {
        return $this->render('@short/default/index.html.twig');
    }

    public function getLinksAction() {
        $baseUrl = $this->generateUrl('indexRoute', array(), UrlGeneratorInterface::ABSOLUTE_URL);

        $result = array(
            array('origin_url' => 'https://www.yandex.ru/', 'alias_url' => $baseUrl.'ffsdfd'),
            array('origin_url' => 'https://www.yandex.ru/', 'alias_url' => $baseUrl.'dddd')
        );

        return new JsonResponse($result);
    }

    public function generateAction(Request $request){
        $data = $request->request->all();
        $t = 5;

    }

    public function redirectAction($url) {
        if($url == "test"){
            return $this->redirect('https://www.yandex.ru/');
        }
        return $this->render('@short/default/error.html.twig');
    }



}