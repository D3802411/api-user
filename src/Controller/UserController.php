<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/api/user', name: 'user_list', methods: ['GET/api/user'])]
    public function list(): Response
    { return $this->json ($user); 
    }

    #[Route('api/user/{id}', name: 'user-id', methods: ['GET/api/user{id}'])]
    public function show($id): Response
    { return $this->json ($user); }

    #[Route('api/user', name: 'create-user', methods: ['POST/api/user'])]
    public function create (Request $request): JsonResponse 
    {
     $data = json_decode($request->getContent(), true);
     $user = new User();
     $user -> setUsername ($data["username"]);
     $entityManager = $this->getDoctrine()->getManager();
     $entityManager ->persist($user);
     $entityManager ->flush();

    return $this->jason (["status"=> "user created", "id" => $user->getId()]);
    }

    #[Route('api/user/{id}', name: 'update-user', methods: ['PUT/api/user{id}'])]
    public function update (Request $request, int $id): JsonResponse
    {
       $data = json_decode($request->getContent(), true);
       return new JsonResponse([
        'success' => true,
        'message' => 'User updated successfully',
        'data' => $users[$id]]);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager ->persist($user);
        $entityManager ->flush();    
    }


    #[Route('api/user/{id}', name: 'delete-user', methods: ['DELETE/api/user{id}'])]
    public function delete (Request $request, int $id): JsonResponse
    { 
        $data = json_decode($request->getContent(), true);
        unset($users[$id]);
        return new jsonResponse ([
         'success' => true,
         'message' => 'User deleted successfully',
        
     ]);

    }



}
