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


    /**
     * Тест создает пользователя, генерирует 2 карты для него
     * Проверяем в бд, сгенерировались ли они
     */
    public function testGetUserCards()
    {
        $user = factory(User::class)->create();
        $cards = factory(Card::class, 2)->create([
            'user_id' => $user->id,
        ]);

        $this->assertTrue(count($cards) === 2);
    }


    /**
     * Тест ищет uuid в базе данных, если его нету, вызываем CardService,
     * где создается пользователь и для него генерируется коллекция карточек,
     * далее проверяет коллекцию на наличие сгенерированных карточек
     */
    public function testGenerateCardsForUser()
    {
        $uuid = '98c01fb3-3238-43b2-820a-3f57f2eb6919';
        $this->notSeeInDatabase('users', ['uuid' => $uuid]);

        $cards = $this->cardService->getUserCards($uuid);
        $this->seeInDatabase('users', ['uuid' => $uuid]);

        $this->assertTrue($cards->isNotEmpty());
    }
}
