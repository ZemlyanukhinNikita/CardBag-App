<?php

namespace tests;

use App\Card;
use app\Repositories\CardRepository;
use app\Repositories\UserRepository;
use App\Service\CardGenerateService;
use App\Service\CardService;
use App\User;
use Laravel\Lumen\Testing\DatabaseTransactions;
use TestCase;

class CardServiceTest extends TestCase
{
    use DatabaseTransactions;
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


    public function testGetUserCards()
    {
        $user = factory(User::class)->create();
        $this->seeInDatabase('users', ['uuid' => $user->uuid]);
        $cards = factory(Card::class, 2)->create([
            'user_id' => $user->id,
        ]);

        if (count($cards) == 2) {
            return $this->assertTrue(true);
        }
        $this->assertTrue(false);
    }


    public function testGenerateCardsForUser()
    {
        $uuid = '96c01fb3-3238-43b2-820a-3f57f2eb6919';
        $this->notSeeInDatabase('users', ['uuid' => $uuid]);

        $cards = $this->cardService->getUserCards($uuid);

        if ((count($cards) >= 0) && (count($cards) <= 10)) {
            return $this->assertTrue(true);
        }
        $this->assertTrue(false);
    }
}
