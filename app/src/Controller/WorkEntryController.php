<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

use App\Contexts\WorkEntry\Application\Query\WorkEntryQuery;
Use App\Contexts\WorkEntry\Application\Command\Register\RegisterCommand; 

use App\Contexts\UserManagement\Application\Auth;
use App\Contexts\UserManagement\Application\Query\UserQuery;
use App\Contexts\WorkEntry\Application\Command\Delete\DeleteCommand;
use App\Contexts\WorkEntry\Application\Command\Update\UpdateCommand;
use Psr\Log\LoggerInterface;

use App\Validation\WorkEntry\Validator as WorkEntryValidator;

class WorkEntryController extends AbstractController
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private Auth $auth, 
        private WorkEntryValidator $validator, 
        private LoggerInterface $logger
    ){}
    
    #[Route('/workentry', name: 'app_get_work_entry', methods: ['GET'])]
    public function get(Request $request, WorkEntryQuery $query, UserQuery $userQuery): JsonResponse
    {
        $token = $request->headers->get('Authorization');
        if (!$this->auth->login(api_token: $token)) {
            return $this->json(['message' => 'Invalid token'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $user_id = $userQuery->getUserId($this->auth->getAuthenticatedUser()->getEmail());

        $entries = array(); 
        foreach ($query->getWorkEntries($user_id) as $work_entry) {
            $entries[] =  [
                'user_id' => $user_id,
                'id' => $work_entry->getId(),
                'start_date' => $work_entry->getStartDate(),
                'end_date' => $work_entry->getEndDate()
            ]; 
        }
        return $this->json($entries, JsonResponse::HTTP_OK);
    }

    #[Route('/workentry', name: 'app_create_work_entry', methods: ['POST'])]
    public function post(Request $request, UserQuery $userQuery): JsonResponse
    {   
        # Auth 
        $token = $request->headers->get('Authorization');
        if (!$this->auth->login(api_token: $token)) {
            return $this->json(['message' => 'Invalid token'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        # Validate Input:
        if (!$this->validator->validatePost($request->request->all())) {
            return $this->json(['message' => 'Invalid request'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $user_id = $userQuery->getUserId($this->auth->getAuthenticatedUser()->getEmail());

        $this->messageBus->dispatch(RegisterCommand::create(
            user_id: $user_id,
            start_date: $request->request->get('start_date'),
            end_date: $request->request->get('end_date')
        ));

        return $this->json(["message" => "Work entry created"], JsonResponse::HTTP_CREATED);
    }

    #[Route('/workentry/{entry_id}', name: 'app_update_work_entry', methods: ['PUT'])]
    public function put(Request $request): JsonResponse
    {  
        $token = $request->headers->get('Authorization');
        if (!$this->auth->login(api_token: $token)) {
            return $this->json(['message' => 'Invalid token'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $this->messageBus->dispatch(
            UpdateCommand::create(
                entry_id: $request->get('entry_id'),
                start_date: $request->get('start_date'),
                end_date: $request->get('end_date')
            )
        ); 

        return $this->json(["message" => "Work entry updated"], JsonResponse::HTTP_CREATED);
    }

    #[Route('/workentry/{entry_id}', name: 'app_delete_work_entry', methods: ['DELETE'])]
    public function delete(Request $request): JsonResponse
    {  
        $token = $request->headers->get('Authorization');
        if (!$this->auth->login(api_token: $token)) {
            return $this->json(['message' => 'Invalid token'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $this->messageBus->dispatch(DeleteCommand::create(entry_id: $request->get('entry_id'))); 

        return $this->json(["message" => "Work entry deleted"], JsonResponse::HTTP_OK);
    }
       
}
