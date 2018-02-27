<?php

namespace app\Repositories;

use App\Network;

class NetworkRepository extends EloquentRepository implements NetworkInterface
{
    public function getModel()
    {
        return new Network();
    }
}