<?php

declare(strict_types=1);

namespace Chubbyphp\Api\Dto\Collection;

use Chubbyphp\Api\Collection\CollectionInterface;

interface CollectionRequestInterface
{
    public function createCollection(): CollectionInterface;
}
