<?php

namespace Tests\Feature;

use App\Entity;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EntityTest extends TestCase
{
    private $token;
    private $token2;
    private $exampleEntityId;

    public function setUp(){
        parent::setUp();

        $userData = [
            'email' => 'mytestemail@email.com',
            'password' => '123456'
        ];

        $userData2 = [
            'email' => 'mytestemail2@email.com',
            'password' => '123456'
        ];

        $response = json_decode($this->post('/register', $userData)->baseResponse->getContent());

        $this->token = $response->token;

        $response2 = json_decode($this->post('/register', $userData2)->baseResponse->getContent());
        $this->token2 = $response2->token;

        $exampleEntity = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->json('POST', '/entity', ['name' => 'M6RsGJKiBxoxxMv5RfJG']);

        $entityObject = json_decode($exampleEntity->baseResponse->getContent());
        $this->exampleEntityId = $entityObject->id;
    }

    public function testCreate()
    {
        $goodResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->json('POST', '/entity', ['name' => 'l2vJEg7B7HVwYWIQIZsb']);

        $goodResponse
            ->assertStatus(200)
            ->assertJsonStructure([
                'user_id',
                'name',
                'updated_at',
                'created_at',
                'id'
            ]);
    }

    public function testGet()
    {
        $goodResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->json('GET', '/entity');

        $goodResponse
            ->assertStatus(200)
            ->assertJsonCount(1);

        $emptyResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token2,
        ])->json('GET', '/entity');

        $emptyResponse
            ->assertStatus(200)
            ->assertJsonCount(0);
    }

    public function testUpdate()
    {
        $goodResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->json('PUT', '/entity/' . $this->exampleEntityId, ['name' => 'M6RsGJKiBxoxxMv5RfJG update']);

        $responseObject = json_decode($goodResponse->baseResponse->getContent());

        $goodResponse
            ->assertStatus(200);

        $this->assertTrue($responseObject->name === 'M6RsGJKiBxoxxMv5RfJG update');

        $missingResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->json('PUT', '/entity/999999999', ['name' => 'M6RsGJKiBxoxxMv5RfJG update']);

        $missingResponse->assertStatus(404);
    }

    public function testDelete()
    {
        $goodResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->json('DELETE', '/entity/' . $this->exampleEntityId);

        $goodResponse->assertStatus(200);

        $missingResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->json('DELETE', '/entity/' . $this->exampleEntityId);

        $missingResponse->assertStatus(404);
    }


    public function tearDown()
    {
        User::where('email', 'mytestemail@email.com')->delete();
        User::where('email', 'mytestemail2@email.com')->delete();
        Entity::where('name', 'M6RsGJKiBxoxxMv5RfJG')->delete();
        Entity::where('name', 'l2vJEg7B7HVwYWIQIZsb')->delete();

        parent::tearDown();
    }
}
