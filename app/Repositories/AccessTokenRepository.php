<?php
namespace app\Repositories;

use App\AccessToken;

class AccessTokenRepository extends EloquentRepository implements AccessTokenInterface
{
    /**
     * {@inheritDoc}
     * Метод возвращения модели Card
     * @return AccessToken
     */
    public function getModel()
    {
        return new AccessToken();
    }
}