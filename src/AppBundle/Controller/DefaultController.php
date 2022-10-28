<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Services\Helpers;

class DefaultController extends Controller {

    public function indexAction(Request $request) {
        echo "esto es el index";
        die();
    }

    public function pruebasAction() {
        $helpers = $this->get(Helpers::class);
        $em = $this->getDoctrine()->getManager();
        $userList = $em->getRepository('BackendBundle:User')->findAll();
//        echo ($helpers->holaMundo());
//        // var_dump($userList);
//        die();

        $data = array(
            'status' => 'success',
            'code' => 200,
            'msg' => 'Jaló',
            'result' => $userList
        );
        return $helpers->json($data);
    }

}
