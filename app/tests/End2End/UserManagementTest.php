<?php declare(strict_types=1);

namespace App\Tests\End2End;

use Symfony\Contracts\HttpClient\HttpClientInterface; 
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase; 

class UserManagementTest extends KernelTestCase 
{   
    private static HttpClientInterface $http_client;
    private static string $email;  

    public static function setUpBeforeClass(): void {
        self::bootKernel();
        self::$http_client = self::getContainer()->get(HttpClientInterface::class); 
        self::$email = bin2hex(random_bytes(10)) . '@example.com';
    }

    public function test_user_can_be_registered(): void {
        
        $response = self::$http_client->request('POST', 'http://localhost/register', [
            'body' => [
                'email' => self::$email,
                'password' => 'pass¡',
                'name' => 'Test User',
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());
    }

    /**
     * @depends test_user_can_be_registered
     */
    public function test_user_can_login(): void {

        $response = self::$http_client->request('POST', 'http://localhost/login', [
            'body' => [
                'email' => self::$email,
                'password' => 'pass¡',
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('api_token', $response->toArray());

    }

    /**
     * @depends test_user_can_login
     */
    public function test_user_can_logout(): void {
        # 1 - Get the token.
        $response = self::$http_client->request('POST', 'http://localhost/login', [
            'body' => [
                'email' => self::$email,
                'password' => 'pass¡',
            ]
        ]);

        $token = $response->toArray()['api_token'];   

        # 2 - Logout
        $response = self::$http_client->request('POST', 'http://localhost/logout', [
            'headers' => [
                'Authorization' => $token
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['message'=>'You logout successfully'], $response->toArray());

        # 3 - Token was changed, I can no longer access url which requires api_token, like, logout. 
        $response = self::$http_client->request('POST', 'http://localhost/logout', [
            'headers' => [
                'Authorization' => $token
            ]
        ]);

        # Unauthorized status:
        $this->assertEquals(401, $response->getStatusCode());
        
    }   
}


