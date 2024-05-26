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

    public function test_workentry_can_be_created(): void {
        $response = self::$http_client->request('POST', 'http://localhost/workentry', [
            'headers' => [
                'Authorization' => self::$token
            ], 
            'body' => [
                'start_date' => '2022-01-01-09:00',
                'end_date' => '2022-01-02-18:00',
            ]   
        ]);
        $this->assertSame(201, $response->getStatusCode());
    }


    /**
     * @depends test_workentry_can_be_created
     */
    public function test_workentries_can_be_read(): void {

        $response = self::$http_client->request('GET', 'http://localhost/workentry', [
            'headers' => [
                'Authorization' => self::$token
            ], 
        ]);
        $this->assertSame(200, $response->getStatusCode());
        # Assert that we get an array of workentries.

        $this->assertNotEmpty($response->toArray()[0]);

    }

    /**
     * 1 - get work entries 
     * 2 - pick one id to update 
     * 3 - send update hitting workentries/entry_id 
     * 4 - check that workentry was updated
     */

    /**
     * @depends test_workentries_can_be_read
     */
    public function test_workentry_can_be_updated(): void {
        
        # get entries 
        $response = self::$http_client->request('GET', 'http://localhost/workentry', [
            'headers' => [
                'Authorization' => self::$token
            ], 
        ]);

        # pick first one  
        $work_entry = $response->toArray()[0]; 

        $workentry_id = $work_entry['id'];
        $initial_start_date = $work_entry['start_date'];

        # Update it 
        $response = self::$http_client->request('POST', 'http://localhost/workentry/' . $workentry_id, [
            'headers' => [
                'Authorization' => self::$token
            ], 
            'body' => [
                'start_date' => '2022-01-01-10:00',
            ]   
        ]);

        $this->assertSame(200, $response->getStatusCode());
        
        # get entries again:
        $response = self::$http_client->request('GET', 'http://localhost/workentry', [
            'headers' => [
                'Authorization' => self::$token
            ], 
        ]);

        # find entry: 
        foreach ($response->toArray() as $entry) 
            if ($entry['id'] == $workentry_id) 
                break; 

        # assert is updated:
        $this->assertNotEquals($initial_start_date, $entry['start_date']);

    }

    /**
     * @depends test_workentry_can_be_updated
     */
    public function test_work_entry_can_be_deleted(): void {
        # get entries
        $response = self::$http_client->request('GET', 'http://localhost/workentry', [
            'headers' => [
                'Authorization' => self::$token
            ], 
        ]);

        $entries = $response->toArray();
        # pick first one
        $workentry_id = $entries[0]['id'];

        # store count:
        $entries_count = count($response->toArray());

        # delete it
        $response = self::$http_client->request('DELETE', 'http://localhost/workentry/' . $workentry_id, [
            'headers' => [
                'Authorization' => self::$token
            ], 
        ]);
        $this->assertSame(200, $response->getStatusCode());

        # get entries again
        $response = self::$http_client->request('GET', 'http://localhost/workentry', [
            'headers' => [
                'Authorization' => self::$token
            ], 
        ]);
        $this->assertSame($entries_count - 1, count($response->toArray()));

        # Assert count is decreased

        $this->assertLessThan($entries_count, count($response->toArray()));

    }


}