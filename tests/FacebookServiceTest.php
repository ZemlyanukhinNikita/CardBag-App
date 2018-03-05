<?php

use App\Service\FacebookService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Kreait\Firebase\ServiceAccount;

class FacebookServiceTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testSuccessfulResponse()
    {
        $token = "token";

        $mock = new MockHandler([
            new Response(200, ["content-type" => 'application/json'], json_encode(['name' => 'Роман Максимов', 'id' => '590387547975564'])),
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $service = new FacebookService($client);

        $userProfile = $service->checkUserTokenInSocialNetwork($token, '590387547975564');

        $this->assertEquals('Роман Максимов', $userProfile->getFullName());
        $this->assertEquals($token, $userProfile->getToken());
        $this->assertEquals('590387547975564', $userProfile->getUid());
    }
}
