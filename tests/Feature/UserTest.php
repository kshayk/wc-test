<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    private $token;

    public function setUp(){
        parent::setUp();

        $userData = [
            'email' => 'mytestemail@email.com',
            'password' => '123456'
        ];

        $response = json_decode($this->post('/register', $userData)->baseResponse->getContent());

        $this->token = $response->token;
    }
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRegister()
    {
        $validData = [
            'email' => 'testrerere@email.com',
            'password' => '123456'
        ];

        $invalidEmail = [
            'email' => 'not_a_real_email',
            'password' => '123456'
        ];

        $invalidPassword = [
            'email' => 'test2@email.com',
            'password' => '12'
        ];

        $emptyData = [];

        $goodResponse = $this->post('/register', $validData);

        $goodResponse->assertStatus(201);
        $goodResponse->assertJsonStructure(['token']);

        User::where('email', 'testrerere@email.com')->delete();

        $invalidEmailResponse = $this->post('/register', $invalidEmail);
        $invalidEmailResponse->assertStatus(400);

        $invalidPasswordResponse = $this->post('/register', $invalidPassword);
        $invalidPasswordResponse->assertStatus(400);

        $emptyDataResponse = $this->post('/register', $emptyData);
        $emptyDataResponse->assertStatus(400);
    }

    public function testToken()
    {
        $validData = [
            'email' => 'mytestemail@email.com',
            'password' => '123456'
        ];

        $invalidData = [
            'email' => 'nonexistingemail@test.com',
            'password' => '123456'
        ];

        $validDataResponse = $this->post('/token', $validData);
        $validDataResponse->assertStatus(200);
        $validDataResponse->assertJsonStructure(['token']);

        $invalidDataResponse = $this->post('/token', $invalidData);
        $invalidDataResponse->assertStatus(400);
    }

    //get an account object based on a token
    public function testAccount()
    {
        $goodResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->json('GET', '/account');

        $goodResponse
            ->assertStatus(200)
            ->assertJsonStructure([
                'user',
            ]);

        //altering the token to get an invalid token error
        $badResponse = $this->withHeaders([
            'Authorization' => 'Bearer L' . $this->token,
        ])->json('GET', '/account');

//        print_r($badResponse->baseResponse->getContent());

        $badResponse->assertJson(['status' => 'Token is Invalid']);
    }

    public function tearDown()
    {
        User::where('email', 'mytestemail@email.com')->delete();
        User::where('email', 'testrerere@email.com')->delete();

        parent::tearDown();
    }
}
