<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BackendBundle\Entity\Task;
use AppBundle\Services\Helpers;

/**
 * Description of TaskController
 *
 * @author lfern
 */
class TaskController extends Controller {

    function showAction() {
        $helpers = $this->get(Helpers::class);
        $em = $this->getDoctrine()->getManager();

        $task = $em->getRepository('BackendBundle:Task')->findAll();

        $data = array(
            'status' => 'success',
            'code' => 200,
            'msg' => 'Registros de Task',
            'Task' => $task
        );
        return $helpers->json($data);
    }

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
            $updateAt = new \DateTime('now');


            $user_id = (isset($params->id)) ? $params->id : null;
            $title = (isset($params->title)) ? $params->title : null;
            $description = (isset($params->description)) ? $params->description : null;
            $status = (isset($params->status)) ? $params->status : null;

            if ($user_id != null && $title != null && $description != null && $status != null) {

                $user = $em->getRepository('BackendBundle:User')->findOneBy(['id' => $user_id]);

                if ($user != null) {
                    $task = new Task();

                    $task->setUser($user);
                    $task->setTitle($title);
                    $task->setDescription($description);
                    $task->setStatus($status);
                    $task->setCreatedAt($createAt);
                    $task->setUpdatedAt($updateAt);

                    $em->persist($task);
                    $em->flush();
                    $data = array(
                        'status' => 'success',
                        'code' => 200,
                        'msg' => 'Registro exitoso',
                        'taskCreated' => $task
                    );
                } else {
                    $data = array(
                        'status' => 'error',
                        'code' => 400,
                        'msg' => 'El usuario no se encontró'
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
            'msg' => 'Algo salió mal :(',
            'params' => $params
        );
        if ($json != null) {
            $updateAt = new \DateTime('now');

            $user_id = (isset($params->id)) ? $params->id : null;
            $task_id = (isset($params->idT)) ? $params->idT : null;
            $title = (isset($params->title)) ? $params->title : null;
            $description = (isset($params->description)) ? $params->description : null;
            $status = (isset($params->status)) ? $params->status : null;

            if ($user_id != null && $title != null && $description != null && $status != null) {

                $user = $em->getRepository('BackendBundle:User')->findOneBy(['id' => $user_id]);
                $task = $em->getRepository('BackendBundle:Task')->findOneBy(['id' => $task_id]);

                if ($user != null && $task != null && $user_id == $task->getUser()->getId()) {

                    $task->setUser($user);
                    $task->setTitle($title);
                    $task->setDescription($description);
                    $task->setStatus($status);
                    $task->setUpdatedAt($updateAt);

                    $em->persist($task);
                    $em->flush();
                    $data = array(
                        'status' => 'success',
                        'code' => 200,
                        'msg' => 'Registro exitoso',
                        'taskCreated' => $task
                    );
                } else {
                    $data = array(
                        'status' => 'error',
                        'code' => 400,
                        'msg' => 'El usuario, la tarea no han sido encontrados o no eres dueño de la tarea'
                    );
                }
            }
        }
        return $helpers->json($data);
    }

}
