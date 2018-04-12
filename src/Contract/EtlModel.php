<?php

namespace Xamplifier\Etl\Contract;

use Xamplifier\Etl\Transformer\Entity;

interface EtlModel
{
    public function etl(Entity $entity);
}
