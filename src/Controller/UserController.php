<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse; 
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request; 
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;


class UserController extends AbstractController
{
    //#[Route(/user', name: 'api_user', methods: ['GET'])]//
    public function index(UserRepository $repository): JsonResponse
    {
        $users=$repository->findAll();
        $data = array_map(function (User $user) {
        return [
            "id" => $user->getId(),
            "password" => $user->getPassword(),
            "email" => $user->getEmail(),
            "userName" => $user->getUserName(),
            "roles" => $user->getRoles(),
        ];
        }, $users); 
        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }


    //#[Route('/user/{id}', name: 'user_id', methods: ['GET'])]//
    public function show(User $user): JsonResponse
    { $data =  [
        "id" => $user->getId(),
        "password" => $user->getPassword(),
        "email" => $user->getEmail(),
        "userName" => $user->getUserName(),
        "roles" => $user->getRoles(),
        ];
        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }


   // #[Route('/user', name: 'create_user', methods: ['POST'])]//
    public function create (Request $request, EntityManagerInterface $em): JsonResponse 
    {
     $data = json_decode($request->getContent(), true);
     $user = new User();
     
     $user->setPassword($data ["password"]);
     $user->setEmail($data ["email"]);
     $user->setUserName($data["userName"]);
     $user->setRoles($data ["role"] ?? ["ROLE-USER"]);
     $em->persist($user);
     $em->flush();

    return new JsonResponse (["status"=> "user created"], JsonResponse::HTTP_CREATED);
    }

   // #[Route('/user/{id}', name: 'update_user', methods: ['PUT'])]//
    public function update(Request $request, User $user, EntityManagerInterface $em): JsonResponse
       {
        $data = json_decode($request->getContent(), true);
        
        if (isset ($data["password"]))
        {
            $user -> setPassword ($data ["password"]);
        };

        if (isset ($data["Email"]))
        {
            $user -> setEmail ($data ["Email"]);
        };   
       
        if (isset ($data["userName"]))
        {
            $user -> setUsername ($data["userName"]);
        };     

        if (isset ($data["Role"]))
        {
            $user -> setRole ($data ["Role"]);
        };        
        $em ->persist($user);
        $em ->flush();


        return new JsonResponse (["status"=> "user updated"], JsonResponse::HTTP_OK);
    }


    //#[Route('api-user/user/{id}', name: 'delete_user', methods: ['DELETE'])]//
    public function delete (Request $request, User $user, EntityManagerInterface $em): JsonResponse
    { 
        $data = json_decode($request->getContent(), true);
     
        $em ->remove($user);
        $em ->flush();
        return new JsonResponse (["status"=> "user deleted"], JsonResponse::HTTP_OK);
    }

}


class TokenController
{
    private JWTTokenManagerInterface $jwtManager;

    public function __construct(JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    public function generateToken(UserInterface $user): JsonResponse
    {
        // Create a token for the authenticated user
        $token = $this->jwtManager->create($user);

        return new JsonResponse(['token' => $token]);
    }
}
