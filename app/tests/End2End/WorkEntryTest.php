<?php declare(strict_types=1);

namespace App\Tests\End2End;

use DateTimeImmutable;
use Symfony\Contracts\HttpClient\HttpClientInterface; 
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase; 

class WorkEntryTest extends KernelTestCase 
{   

    private static $http_client;
    private static string $token; 

    public static function setUpBeforeClass(): void {
        self::bootKernel();
        self::$http_client = self::getContainer()->get(HttpClientInterface::class); 

        # Create an user and get the token first: 
        $email = bin2hex(random_bytes(10)) . '@test.com';
        $response = self::$http_client->request('POST', 'http://localhost/register', [
            'body' => [
                'email' => $email, 
                'password' => 'pass¡',
                'name' => 'Test User',
            ]
        ]);

        $response = self::$http_client->request('POST', 'http://localhost/login', [
            'body' => [
                'email' => $email,
                'password' => 'pass¡',
            ]
        ]);

        self::$token = $response->toArray()['api_token'];

    }
    public function test_ping_get_url(): void {
        $response = self::$http_client->request('GET', 'http://localhost/workentry'); 
        $this->assertSame(200, $response->getStatusCode()); 
    }

    public function test_ping_post_url(): void {
        $response = self::$http_client->request('POST', 'http://localhost/workentry'); 

        $this->assertSame(200, $response->getStatusCode()); 
    }

    public function test_ping_put_url(): void {
        $response = self::$http_client->request('PUT', 'http://localhost/workentry/uuid1'); 
        $this->assertSame(200, $response->getStatusCode()); 
    }

    public function test_ping_delete_url(): void {
        $response = self::$http_client->request('DELETE', 'http://localhost/workentry/uuid2'); 
        $this->assertSame(200, $response->getStatusCode()); 
    }

    public function test_workentry_can_be_created(): void {


        $response = self::$http_client->request('POST', 'http://localhost/workentry', [
            'headers' => [
                'Authorization' => self::$token
            ], 
            'body' => [
                'start_date' => '2022-01-01',
                'end_date' => '2022-01-02',
            ]   
        ]);
        $this->assertSame(201, $response->getStatusCode());
    }


    public function test_workentries_can_be_read(): void {

        $response = self::$http_client->request('GET', 'http://localhost/workentry', [
            'headers' => [
                'Authorization' => self::$token
            ], 
        ]);
        $this->assertSame(200, $response->getStatusCode());
        # Assert that we get an array of workentries.
    }

    public function test_workentry_can_be_updated(): void {
        /**
         * 1 - get work entries 
         * 2 - pick one id to update 
         * 3 - send update hitting workentries/entry_id 
         * 4 - check that workentry was updated
         */
    }


    public function test_work_entry_can_be_deleted(): void {
        /**
         * 1 - get work entries and save them 
         * 2 - pick one to delete
         * 3 - send delete hitting workentries/entry_id
         * 4 - get workentries again 
         * 5 - check that workentry is no longer there. 
         */
    }



}