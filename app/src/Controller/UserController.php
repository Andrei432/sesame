<?php

namespace App\Controller;

# Symfony dependencies
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

# Our package dependencies
use App\Contexts\UserManagement\Application\Command\RefreshToken\RefreshTokenCommand;
use App\Contexts\UserManagement\Application\Command\Register\RegisterCommand;
use App\Contexts\UserManagement\Application\Query\UserQuery;

# This class is only responsible for handling classic web stuff
# like validation against a schema or access interception and queueing commands to message bus
# or querying the context for what it needs. 

# We want to focus on showing DDD concepts. 
# We assume data comes with proper structure and dont integrate with external services.
# This is not production-level software.

class UserController extends AbstractController
{   

    # Use property promotion to avoid constructror boilerplate. 
    public function __construct(private MessageBusInterface $messageBus){}

    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {      
        $command = RegisterCommand::create(
            email: $request->get('email'),
            password: $request->get('password'),
            name: $request->get('name'),
        ); 

        $this->messageBus->dispatch($command);

        return $this->json([
            "message" => "User registered"
        ]); 
    }


    #[Route('/login', name: 'app_login', methods: ['POST'])]
    public function login(Request $request, UserQuery $query): JsonResponse
    {
        # Check if user exists in the database with the given credentials
        $user = $query->getUser(
            email: $request->request->get('email'), 
            password: $request->request->get('password')
        );
        
        # If it doesn't exist, return an error
        if ($user === null) {
            return $this->json([
                "message" => "Invalid credentials"
            ], JsonResponse::HTTP_UNAUTHORIZED); 
        } 

        # If it exists, return the token from the UserDTO. 
        return $this->json(['Your Api Token is' => $user->getApiToken()]); 

    }

    #[Route('/logout', name: 'app_logout', methods:['POST'])]
    public function logout(Request $request, UserQuery $query): JsonResponse {
        
        $token = $request->headers->get('Authorization'); 

        if ($token === null) {
            return $this->json(['message' => 'You need to set the Authorization header with your token']);
        }

        if (!$query->userExistsByToken($token)) {
            return $this->json(['message' => 'Your token is invalid']);
        }

        # When user logs out, we refresh the token, so that it's no longer valid
        # We issue a new one if user hits login. 
        $this->messageBus->dispatch(RefreshTokenCommand::create(email: $request->request->get('email')));
        return $this->json(['message' => 'You logout successfully']); 
    }
 
}