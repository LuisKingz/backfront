<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\User;
use AppBundle\Services\Helpers;

class UserController extends Controller {

    function newAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);

        $data = array(
            'status' => 'error',
            'code' => 400,
            'msg' => 'Algo salió mal :('
        );
        if ($json != null) {
            $createAt = new \DateTime('now');
            $role = 'ROLE_USER';

            $email = (isset($params->email)) ? $params->email : null;
            $name = (isset($params->name)) ? $params->name : null;
            $surname = (isset($params->email)) ? $params->surname : null;
            $password = (isset($params->password)) ? $params->password : null;

            $emailConstraint = new Assert\Email();
            $emailConstraint->message = 'Correo no valido';
            $validate_email = $this->get('validator')->validate($email, $emailConstraint);

            if ($email != null && count($validate_email) == 0 && $password != null && $surname != null && $name != null) {

                $duplicado = $em->getRepository('BackendBundle:User')->findBy(['email' => $email]);
                if (count($duplicado) == 0) {
                    $user = new User();

                    $user->setName($name);
                    $user->setSurname($surname);
                    $user->setEmail($email);
                    $user->setPassword($password);
                    $user->setRole($role);
                    $user->setCreatedAt($createAt);

                    $em->persist($user);
                    $em->flush();
                    $data = array(
                        'status' => 'success',
                        'code' => 200,
                        'msg' => 'Registro exitoso',
                    );
                } else {
                    $data = array(
                        'status' => 'error',
                        'code' => 400,
                        'msg' => 'El usuario ya exite'
                    );
                }
            }
        }
        return $helpers->json($data);
    }

    function updateAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);

        $data = array(
            'status' => 'error',
            'code' => 400,
            'msg' => 'Algo salió mal :('
        );
        if ($json != null) {
            $createAt = new \DateTime('now');
            $role = 'ROLE_USER';
            $id = (isset($params->id)) ? $params->id : null;
            $email = (isset($params->email)) ? $params->email : null;
            $name = (isset($params->name)) ? $params->name : null;
            $surname = (isset($params->email)) ? $params->surname : null;
            $password = (isset($params->password)) ? $params->password : null;

            $emailConstraint = new Assert\Email();
            $emailConstraint->message = 'Correo no valido';
            $validate_email = $this->get('validator')->validate($email, $emailConstraint);

            if ($id != null && $email != null && count($validate_email) == 0 && $password != null && $surname != null && $name != null) {
                $duplicado = $em->getRepository('BackendBundle:User')->findOneBy(['id' => $id]);

                if ($duplicado != null) {
                    $correoDuplicado = $em->getRepository('BackendBundle:User')->findBy(['email' => $email]);
                    if (count($correoDuplicado) != 0) {
                        $data = array(
                            'status' => 'error',
                            'code' => 400,
                            'msg' => 'Correo ya registrado, no se puede modificar'
                        );
                    } else {
                        $duplicado->setName($name);
                        $duplicado->setSurname($surname);
                        $duplicado->setEmail($email);
                        $duplicado->setPassword($password);
                        $duplicado->setRole($role);
                        $duplicado->setCreatedAt($createAt);

                        $em->persist($duplicado);
                        $em->flush();
                        $data = array(
                            'status' => 'success',
                            'code' => 200,
                            'msg' => 'Modificacion exitosa',
                        );
                    }
                } else {
                    $data = array(
                        'status' => 'error',
                        'code' => 400,
                        'msg' => 'No se ha encontrado al usuario'
                    );
                }
            }
        }

        return $helpers->json($data);
    }
}
