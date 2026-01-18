<?php

declare(strict_types=1);

namespace Chubbyphp\Api\Dto\Collection;

interface CollectionSortInterface extends \JsonSerializable
{
    /**
     * @return array<string, null|string>
     */
    public function jsonSerialize(): array;
}
