<?php

declare(strict_types=1);

namespace Chubbyphp\Api\Dto\Collection;

use Chubbyphp\Api\Dto\Model\ModelResponseInterface;

abstract readonly class AbstractReadonlyCollectionResponse implements CollectionResponseInterface
{
    /**
     * @param array<ModelResponseInterface> $items
     * @param array<string, array{
     *   href: string,
     *   templated: bool,
     *   rel: array<string>,
     *   attributes: array<string, string>
     * }> $_links
     */
    public function __construct(
        public int $offset,
        public int $limit,
        public CollectionFiltersInterface $filters,
        public CollectionSortInterface $sort,
        public array $items,
        public int $count,
        public string $_type,
        public array $_links
    ) {}

    /**
     * @return array{
     *   offset: int,
     *   limit: int,
     *   filters: array<string, null|string>,
     *   sort: array<string, null|string>,
     *   items: array<array{
     *     id: string,
     *     createdAt: string,
     *     updatedAt: null|string,
     *     _type: string,
     *     _links: array<string, array{
     *       href: string,
     *       templated: bool,
     *       rel: array<string>,
     *       attributes: array<string, string>
     *     }>,
     *     ...
     *   }>,
     *   count: int,
     *   _type: string,
     *   _links: array<string, array{
     *     href: string,
     *     templated: bool,
     *     rel: array<string>,
     *     attributes: array<string, string>
     *   }>
     * }
     */
    final public function jsonSerialize(): array
    {
        return [
            'offset' => $this->offset,
            'limit' => $this->limit,
            'filters' => $this->filters->jsonSerialize(),
            'sort' => $this->sort->jsonSerialize(),
            'items' => array_map(
                static fn (ModelResponseInterface $modelResponse) => $modelResponse->jsonSerialize(),
                $this->items
            ),
            'count' => $this->count,
            '_type' => $this->_type,
            '_links' => $this->_links,
        ];
    }
}
