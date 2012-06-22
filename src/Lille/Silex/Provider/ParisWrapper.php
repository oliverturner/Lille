<?php

namespace Lille\Silex\Provider;

use J4mie\Paris\Model;
use J4mie\Paris\ORMWrapper as ORM;

class ParisWrapper
{
    public function getModel($modelName)
    {
        return Model::factory($modelName);
    }

    public function getLastQuery()
    {
        return ORM::get_last_query();
    }

    public function getQueryLog()
    {
        return ORM::get_query_log();
    }
}
