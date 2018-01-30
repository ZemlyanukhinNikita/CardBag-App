<?php

namespace tests;

use app\Repositories\CardRepository;
use app\Repositories\UserRepository;
use App\Service\CardGenerateService;
use App\Service\CardService;
use TestCase;

class CardServiceTest extends TestCase
{
    private $userRepo;
    private $cardRepo;
    private $cardGenerateService;
    private $cardService;

    public function setUp()
    {
        parent::setUp();
        $this->userRepo = new UserRepository();
        $this->cardRepo = new CardRepository();
        $this->cardGenerateService = new CardGenerateService();
        $this->cardService = new CardService($this->userRepo, $this->cardRepo, $this->cardGenerateService);
    }


    public function testGetCards()
    {
        $uuid = 'a5124bbc-ab56-4798-bd53-9535c6d6bfb8';
        $this->seeInDatabase('users', ['uuid' => $uuid]);

        $userCards = $this->cardService->getUserCards($uuid);
        $testCardsResponse = [
            [
                'id' => 126,
                'user_id' => 16,
                'title' => 'Drake Stokes',
                'category_id' => 4,
                'front_photo' => 'https://lorempixel.com/640/480/?73296',
                'back_photo' => 'https://lorempixel.com/640/480/?71724',
                'discount' => 79,
                'created_at' => '2018-01-24 08:49:29',
                'updated_at' => '2018-01-24 08:49:29'

            ]
        ];

        $this->assertEquals(json_encode($userCards), json_encode($testCardsResponse));

    }

    public function testHasNotUuid()
    {
        $uuid = 'a5124bbc-ab56-4798-bd53-9535c6d6bf11';
        $this->notSeeInDatabase('users', ['uuid' => $uuid]);
    }
}
