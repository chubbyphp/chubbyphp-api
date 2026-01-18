<?php

declare(strict_types=1);

namespace Chubbyphp\Api\Dto\Model;

use Chubbyphp\Api\Model\ModelInterface;

interface ModelRequestInterface
{
    public function createModel(): ModelInterface;

    public function updateModel(ModelInterface $model): ModelInterface;
}
