<?php declare(strict_types=1);

namespace App\Contexts\UserManagement\Application\Command\RefreshToken;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

use App\Contexts\UserManagement\Domain\UserService;
use RuntimeException;


#[AsMessageHandler]
class RefreshTokenHandler 
{   
    public function __construct( private UserService $userService) {}

    public function __invoke(RefreshTokenCommand $command): void {
        try {
            $this->userService->logout($command->api_token);
        } catch (\Exception $e) {
            throw new RuntimeException('Failed to refresh token. ' . $e->getMessage());
        }
     
    }
}