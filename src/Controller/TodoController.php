<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{
    #[Route('/todo', name: 'app_todo')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->has('todos')) {
            $todos = [
                'achat' => 'achter clé usb',
                'cours' => 'finaliser le cours',
                'correction' => 'corriger mes examens'
            ];
            $session->set('todos', $todos);
            $this->addFlash('info', "List Initialized!");
        }
        return $this->render('todo/index.html.twig');

    }

    #[Route('/todo/add/{name}/{content}', name: 'add_todo')]
    public function addtodo(Request $request, $name, $content):Response
    {
        $session = $request->getSession();
        if ($session->has('todos')) {
            $todos = $session->get('todos');
            if (isset($todos[$name])) {
                $this->addFlash('error', "Todo Exist Already In List");
            } else {
                $todos[$name] = $content;
                $session->set('todos',$todos);
                $this->addFlash('success', "Todo Added Successfully");
            }
        } else {
            $this->addFlash('error', "List Not Initialized Yet!");
        }
        return $this->redirectToRoute('app_todo');
    }

    #[Route('/todo/update/{name}/{content}', name: 'update_todo')]
    public function updatetodo(Request $request, $name, $content):Response
{
    $session = $request->getSession();
    if ($session->has('todos')) {
        $todos = $session->get('todos');
        if (!isset($todos[$name])) {
            $this->addFlash('error', "Todo Doesn't Exist In List");
        } else {
            $todos[$name] = $content;
            $session->set('todos',$todos);
            $this->addFlash('success', "Todo Updated Successfully");
        }
    } else {
        $this->addFlash('error', "List Not Initialized Yet!");
    }
    return $this->redirectToRoute('app_todo');
}
    #[Route('/todo/delete/{name}', name: 'delete_todo')]
    public function deletetodo(Request $request, $name):Response
{
    $session = $request->getSession();
    if ($session->has('todos')) {
        $todos = $session->get('todos');
        if (!isset($todos[$name])) {
            $this->addFlash('error', "Todo Doesn't Exist In List");
        } else {
            unset($todos[$name]);
            $session->set('todos',$todos);
            $this->addFlash('success', "Todo Deleted Successfully");
        }
    } else {
        $this->addFlash('error', "List Not Initialized Yet!");
    }
    return $this->redirectToRoute('app_todo');
}
    #[Route('/todo/reset', name: 'reset_todo')]
    public function resettodo(Request $request):Response
    {
        $session = $request->getSession();
        $session->remove('todos');
        return $this->redirectToRoute('app_todo');
    }
}
