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
use ShortLinkBundle\Entity\Url;


class ShortController extends Controller
{
    public function indexAction() {
        return $this->render('@short/default/index.html.twig');
    }

    public function getLinksAction(Request $request) {
        $urls = array();
        $session = $request->getSession();
        $userId = $session->get('user_id');
        if(!empty($userId)){
            $repository = $this->getDoctrine()->getRepository('ShortLinkBundle:Url');
            $urls = $repository->findByUserId($userId);
        }

        $result = array();
        $baseUrl = $this->generateUrl('indexRoute', array(), UrlGeneratorInterface::ABSOLUTE_URL);
        foreach ($urls as $index => $url){
            $result[] = array('origin_url' => $url->getOrigin(), 'alias_url' => $baseUrl.$url->getAlias());
        }
        return new JsonResponse($result);
    }

    public function generateAction(Request $request){
        $data = $request->request->all();

        $original = isset($data['original']) ? $data['original'] : null;
        $alias =  isset($data['alias']) ? $data['alias'] : null;

        if(isset($original)){
            if($this->isValidUrl($original)){
                if(isset($alias)){
                    $repository = $this->getDoctrine()->getRepository('ShortLinkBundle:Url');
                    $url = $repository->findOneByAlias($alias);
                    if($url != null){
                        return new JsonResponse(array('status' => 'error', 'msg' => "The alias '{$alias}' already exist"));
                    }
                }

                $session = $request->getSession();
                $userId = $session->get('user_id');
                if(empty($userId)){
                    $userId = uniqid();
                    $session->set('user_id', $userId);
                }

                $alias = !isset($alias) ? $this->generateShortUrl() : $alias;

                $newUrl = new Url();
                $newUrl->setAlias($alias);
                $newUrl->setOrigin($original);
                $newUrl->setUserId($userId);

                $em = $this->getDoctrine()->getManager();
                $em->persist($newUrl);
                $em->flush();

                return new JsonResponse(array('status' => 'success', 'msg' => "Url was generated"));
            }
            return new JsonResponse(array('status' => 'error', 'msg' => "Invalid URL"));
        }
        return new JsonResponse(array('status' => 'error', 'msg' => "Field URL must have value!"));
    }

    public function redirectAction($url) {
        $repository = $this->getDoctrine()->getRepository('ShortLinkBundle:Url');
        $url = $repository->findOneByAlias($url);

        if($url != null){
            return $this->redirect($url->getOrigin());
        }
        return $this->render('@short/default/error.html.twig');
    }

    private function isValidUrl($url){
        $valid = false;
        if( $curl = curl_init() ) {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($curl);
            if($result != false){
                $info = curl_getinfo($curl);
                if($info['http_code'] >= 200 && $info['http_code'] < 400){
                    $valid = true;
                }
            }
            curl_close($curl);
        }
        return $valid;
    }

    private function generateShortUrl(){
        return substr(md5(uniqid()), 0, 8);
    }
}