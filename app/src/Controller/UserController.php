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
use App\Contexts\UserManagement\Application\Auth; 


use Psr\Log\LoggerInterface; 

use App\Validation\User\Validator as UserValidator;  

# This class is only responsible for handling classic web stuff
# like validation against a schema or access interception and queueing commands to message bus
# or querying the context for what it needs. 

# We want to focus on showing DDD concepts. 
# We assume data comes with proper structure and dont integrate with external services.
# This is not production-level software.

class UserController extends AbstractController
{   
    # Use property promotion to avoid constructror boilerplate. 
    public function __construct(
        private MessageBusInterface $messageBus, 
        private Auth $auth, 
        private UserValidator $validator, 
        private LoggerInterface $logger
    ){}

    #[Route('/ping', name: 'app_ping', methods: ['GET'])]
    public function ping(): JsonResponse
    {
        return $this->json(['message' => 'pong']);
    }

    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {      
        # We pass the array, less abstract data type, to avoid validator dependency with Request. 
        if (!$this->validator->validateUserRegisterRequest($request->request->all())) {
            $this->logger->warning("Invalid request: " . json_encode($request->request->all()));
            return $this->json([
                "message" => "Invalid request"
            ], JsonResponse::HTTP_BAD_REQUEST); 
        }
        
        $command = RegisterCommand::create(
            email: $request->get('email'),
            password: $request->get('password'),
            name: $request->get('name'),
        ); 

        $this->messageBus->dispatch($command);

        return $this->json(["message" => "User registered"], JsonResponse::HTTP_CREATED); 
    }


    #[Route('/login', name: 'app_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {   
        # Validate Input: 
        if (!$this->validator->validateUserLoginRequest($request->request->all())) {
            return $this->json([
                "message" => "Invalid request"
            ], JsonResponse::HTTP_BAD_REQUEST); 
        }

        # Authenticate User:
        if (!$this->auth->login($request->get('email'), $request->get('password'))) {
            return $this->json(['message' => 'Invalid email or password'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        # If it exists, return the token from the UserDTO. 
        return $this->json(['api_token' => $this->auth->getAuthenticatedUser()->getApiToken()]); 

    }

    #[Route('/logout', name: 'app_logout', methods:['POST'])]
    public function logout(Request $request): JsonResponse {
        # Validate request. 
        $token = $request->headers->get('Authorization'); 
        if ($token === null) {
            return $this->json(['message' => 'You need to set the Authorization header with your token']);
            $this->logger->warning("token is null"); 
        }

        if (!$this->auth->login(api_token: $token)) {
            $this->logger->warning("Invalid token: " . $token);

            return $this->json(['message' => 'Invalid token'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        # When user logs out, we refresh the token, so that it's no longer valid
        # We issue a new one if user hits login. 
        $this->messageBus->dispatch(RefreshTokenCommand::create(api_token: $token));
        return $this->json(['message' => 'You logout successfully']); 
    }
 
}