<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Api\Unit\Dto\Collection;

use Chubbyphp\Api\Dto\Collection\AbstractReadonlyCollectionResponse;
use Chubbyphp\Api\Dto\Collection\CollectionFiltersInterface;
use Chubbyphp\Api\Dto\Collection\CollectionSortInterface;
use Chubbyphp\Api\Dto\Model\ModelResponseInterface;
use Chubbyphp\Mock\MockMethod\WithReturn;
use Chubbyphp\Mock\MockObjectBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Api\Dto\Collection\AbstractReadonlyCollectionResponse
 *
 * @internal
 */
final class ReadonlyCollectionResponseTest extends TestCase
{
    public function testJsonSerialize(): void
    {
        $builder = new MockObjectBuilder();

        /** @var ModelResponseInterface $modelResponse */
        $modelResponse = $builder->create(ModelResponseInterface::class, [
            new WithReturn(
                'jsonSerialize',
                [],
                ['id' => '111d1691-8486-4447-997c-d10ce35d1fea']
            ),
        ]);

        $collectionResponse = $this->getCollectionResponse($modelResponse);

        self::assertSame([
            'offset' => 0,
            'limit' => 20,
            'filters' => [
                'name' => 'John',
            ],
            'sort' => [
                'name' => 'asc',
            ],
            'items' => [
                [
                    'id' => '111d1691-8486-4447-997c-d10ce35d1fea',
                ],
            ],
            'count' => 1,
            '_type' => 'unknown',
            '_links' => [],
        ], $collectionResponse->jsonSerialize());
    }

    protected function getCollectionResponse(ModelResponseInterface $modelResponse): AbstractReadonlyCollectionResponse
    {
        return new readonly class(
            0,
            20,
            new class implements CollectionFiltersInterface {
                public function jsonSerialize(): array
                {
                    return ['name' => 'John'];
                }
            },
            new class implements CollectionSortInterface {
                public function jsonSerialize(): array
                {
                    return ['name' => 'asc'];
                }
            },
            [$modelResponse],
            1,
            'unknown',
            []
        ) extends AbstractReadonlyCollectionResponse {};
    }
}
