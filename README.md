# chubbyphp-api

[![CI](https://github.com/chubbyphp/chubbyphp-api/actions/workflows/ci.yml/badge.svg)](https://github.com/chubbyphp/chubbyphp-api/actions/workflows/ci.yml)
[![Coverage Status](https://coveralls.io/repos/github/chubbyphp/chubbyphp-api/badge.svg?branch=master)](https://coveralls.io/github/chubbyphp/chubbyphp-api?branch=master)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fchubbyphp%2Fchubbyphp-api%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/chubbyphp/chubbyphp-api/master)
[![Latest Stable Version](https://poser.pugx.org/chubbyphp/chubbyphp-api/v)](https://packagist.org/packages/chubbyphp/chubbyphp-api)
[![Total Downloads](https://poser.pugx.org/chubbyphp/chubbyphp-api/downloads)](https://packagist.org/packages/chubbyphp/chubbyphp-api)
[![Monthly Downloads](https://poser.pugx.org/chubbyphp/chubbyphp-api/d/monthly)](https://packagist.org/packages/chubbyphp/chubbyphp-api)

[![bugs](https://sonarcloud.io/api/project_badges/measure?project=chubbyphp_chubbyphp-api&metric=bugs)](https://sonarcloud.io/dashboard?id=chubbyphp_chubbyphp-api)
[![code_smells](https://sonarcloud.io/api/project_badges/measure?project=chubbyphp_chubbyphp-api&metric=code_smells)](https://sonarcloud.io/dashboard?id=chubbyphp_chubbyphp-api)
[![coverage](https://sonarcloud.io/api/project_badges/measure?project=chubbyphp_chubbyphp-api&metric=coverage)](https://sonarcloud.io/dashboard?id=chubbyphp_chubbyphp-api)
[![duplicated_lines_density](https://sonarcloud.io/api/project_badges/measure?project=chubbyphp_chubbyphp-api&metric=duplicated_lines_density)](https://sonarcloud.io/dashboard?id=chubbyphp_chubbyphp-api)
[![ncloc](https://sonarcloud.io/api/project_badges/measure?project=chubbyphp_chubbyphp-api&metric=ncloc)](https://sonarcloud.io/dashboard?id=chubbyphp_chubbyphp-api)
[![sqale_rating](https://sonarcloud.io/api/project_badges/measure?project=chubbyphp_chubbyphp-api&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=chubbyphp_chubbyphp-api)
[![alert_status](https://sonarcloud.io/api/project_badges/measure?project=chubbyphp_chubbyphp-api&metric=alert_status)](https://sonarcloud.io/dashboard?id=chubbyphp_chubbyphp-api)
[![reliability_rating](https://sonarcloud.io/api/project_badges/measure?project=chubbyphp_chubbyphp-api&metric=reliability_rating)](https://sonarcloud.io/dashboard?id=chubbyphp_chubbyphp-api)
[![security_rating](https://sonarcloud.io/api/project_badges/measure?project=chubbyphp_chubbyphp-api&metric=security_rating)](https://sonarcloud.io/dashboard?id=chubbyphp_chubbyphp-api)
[![sqale_index](https://sonarcloud.io/api/project_badges/measure?project=chubbyphp_chubbyphp-api&metric=sqale_index)](https://sonarcloud.io/dashboard?id=chubbyphp_chubbyphp-api)
[![vulnerabilities](https://sonarcloud.io/api/project_badges/measure?project=chubbyphp_chubbyphp-api&metric=vulnerabilities)](https://sonarcloud.io/dashboard?id=chubbyphp_chubbyphp-api)

## Description

A set of CRUD middleware and request handlers for building APIs with PSR-15.

## Requirements

 * php: ^8.3
 * [chubbyphp/chubbyphp-decode-encode][2]: ^1.3.1
 * [chubbyphp/chubbyphp-http-exception][3]: ^1.3.2
 * [chubbyphp/chubbyphp-parsing][4]: ^2.2
 * [psr/container][5]: ^1.1.2|^2.0.2
 * [psr/http-message][6]: ^1.1|^2.0
 * [psr/http-server-handler][7]: ^1.0.2
 * [psr/http-server-middleware][8]: ^1.0.2
 * [ramsey/uuid][9]: ^4.9.2

## Installation

Through [Composer](http://getcomposer.org) as [chubbyphp/chubbyphp-api][1].

```sh
composer require chubbyphp/chubbyphp-api "^1.0"
```

## Usage

- [Model](#model)
- [Collection](#collection)
- [DTOs](#dtos)
  - [Model Request](#model-request)
  - [Model Response](#model-response)
  - [Collection Request](#collection-request)
  - [Collection Response](#collection-response)
- [Parsing](#parsing)
- [Repository](#repository)
- [Request Handlers](#request-handlers)

### Model

Implement `ModelInterface` for your domain models. Models must provide an ID, timestamps, and JSON serialization.

```php
<?php

declare(strict_types=1);

namespace App\Pet\Model;

use Chubbyphp\Api\Model\ModelInterface;
use Ramsey\Uuid\Uuid;

final class Pet implements ModelInterface
{
    private string $id;
    private \DateTimeInterface $createdAt;
    private ?\DateTimeInterface $updatedAt = null;
    private ?string $name = null;
    private ?string $tag = null;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): string { return $this->id; }
    public function getCreatedAt(): \DateTimeInterface { return $this->createdAt; }
    public function setUpdatedAt(\DateTimeInterface $updatedAt): void { $this->updatedAt = $updatedAt; }
    public function getUpdatedAt(): ?\DateTimeInterface { return $this->updatedAt; }

    public function setName(string $name): void { $this->name = $name; }
    public function getName(): ?string { return $this->name; }
    public function setTag(?string $tag): void { $this->tag = $tag; }
    public function getTag(): ?string { return $this->tag; }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'name' => $this->name,
            'tag' => $this->tag,
        ];
    }
}
```

### Collection

Extend `AbstractCollection` for paginated lists of models with filtering and sorting support.

```php
<?php

declare(strict_types=1);

namespace App\Pet\Collection;

use Chubbyphp\Api\Collection\AbstractCollection;

final class PetCollection extends AbstractCollection {}
```

The abstract class provides: `offset`, `limit`, `filters`, `sort`, `count`, and `items`.

### DTOs

Data Transfer Objects for request/response transformations.

#### Model Request

Implement `ModelRequestInterface` to handle create and update operations.

```php
<?php

declare(strict_types=1);

namespace App\Pet\Dto\Model;

use App\Pet\Model\Pet;
use Chubbyphp\Api\Dto\Model\ModelRequestInterface;
use Chubbyphp\Api\Model\ModelInterface;

final class PetRequest implements ModelRequestInterface
{
    public string $name;
    public ?string $tag = null;

    public function createModel(): ModelInterface
    {
        $model = new Pet();
        $model->setName($this->name);
        $model->setTag($this->tag);

        return $model;
    }

    public function updateModel(ModelInterface $model): ModelInterface
    {
        $model->setUpdatedAt(new \DateTimeImmutable());
        $model->setName($this->name);
        $model->setTag($this->tag);

        return $model;
    }
}
```

#### Model Response

Implement `ModelResponseInterface` for API responses with HATEOAS links.

```php
<?php

declare(strict_types=1);

namespace App\Pet\Dto\Model;

use Chubbyphp\Api\Dto\Model\ModelResponseInterface;

final class PetResponse implements ModelResponseInterface
{
    public string $id;
    public string $createdAt;
    public ?string $updatedAt = null;
    public string $name;
    public ?string $tag = null;
    public string $_type;
    public array $_links;

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'name' => $this->name,
            'tag' => $this->tag,
            '_type' => $this->_type,
            '_links' => $this->_links,
        ];
    }
}
```

#### Collection Request

Implement `CollectionRequestInterface` with filter and sort classes.

```php
<?php

declare(strict_types=1);

namespace App\Pet\Dto\Collection;

use App\Pet\Collection\PetCollection;
use Chubbyphp\Api\Collection\CollectionInterface;
use Chubbyphp\Api\Dto\Collection\CollectionRequestInterface;

final class PetCollectionRequest implements CollectionRequestInterface
{
    public int $offset;
    public int $limit;
    public PetCollectionFilters $filters;
    public PetCollectionSort $sort;

    public function createCollection(): CollectionInterface
    {
        $collection = new PetCollection();
        $collection->setOffset($this->offset);
        $collection->setLimit($this->limit);
        $collection->setFilters((array) $this->filters);
        $collection->setSort((array) $this->sort);

        return $collection;
    }
}
```

#### Collection Response

```php
<?php

declare(strict_types=1);

namespace App\Pet\Dto\Collection;

use Chubbyphp\Api\Dto\Collection\CollectionFiltersInterface;

final class PetCollectionFilters implements CollectionFiltersInterface
{
    public ?string $name = null;

    public function jsonSerialize(): array
    {
        return ['name' => $this->name];
    }
}
```

```php
<?php

declare(strict_types=1);

namespace App\Pet\Dto\Collection;

use Chubbyphp\Api\Dto\Collection\CollectionSortInterface;

final class PetCollectionSort implements CollectionSortInterface
{
    public ?string $name = null;

    public function jsonSerialize(): array
    {
        return ['name' => $this->name];
    }
}
```

Extend `AbstractCollectionResponse` for paginated API responses.

```php
<?php

declare(strict_types=1);

namespace App\Pet\Dto\Collection;

use App\Pet\Dto\Model\PetResponse;
use Chubbyphp\Api\Dto\Collection\AbstractCollectionResponse;

final class PetCollectionResponse extends AbstractCollectionResponse
{
    public PetCollectionFilters $filters;
    public PetCollectionSort $sort;

    public array $items;

    protected function getFilters(): PetCollectionFilters { return $this->filters; }
    protected function getSort(): PetCollectionSort { return $this->sort; }
}
```

### Parsing

Implement `ParsingInterface` to define schemas for request/response transformation using [chubbyphp/chubbyphp-parsing][4].

```php
<?php

declare(strict_types=1);

namespace App\Pet\Parsing;

use App\Pet\Dto\Collection\{PetCollectionFilters, PetCollectionRequest, PetCollectionResponse, PetCollectionSort};
use App\Pet\Dto\Model\{PetRequest, PetResponse};
use Chubbyphp\Api\Collection\CollectionInterface;
use Chubbyphp\Api\Parsing\ParsingInterface;
use Chubbyphp\Framework\Router\UrlGeneratorInterface;
use Chubbyphp\Parsing\ParserInterface;
use Chubbyphp\Parsing\Schema\ObjectSchemaInterface;
use Psr\Http\Message\ServerRequestInterface;

final class PetParsing implements ParsingInterface
{
    public function __construct(
        private readonly ParserInterface $parser,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {}

    public function getCollectionRequestSchema(ServerRequestInterface $request): ObjectSchemaInterface
    {
        $p = $this->parser;

        return $p->object([
            'offset' => $p->union([$p->string()->toInt(), $p->int()->default(0)]),
            'limit' => $p->union([$p->string()->toInt(), $p->int()->default(CollectionInterface::LIMIT)]),
            'filters' => $p->object([
                'name' => $p->string()->nullable()->default(null),
            ], PetCollectionFilters::class)->strict()->default([]),
            'sort' => $p->object([
                'name' => $p->union([$p->literal('asc'), $p->literal('desc')])->nullable()->default(null),
            ], PetCollectionSort::class)->strict()->default([]),
        ], PetCollectionRequest::class)->strict();
    }

    public function getCollectionResponseSchema(ServerRequestInterface $request): ObjectSchemaInterface
    {
        $p = $this->parser;

        return $p->object([
            'offset' => $p->int(),
            'limit' => $p->int(),
            'filters' => $p->object(['name' => $p->string()->nullable()], PetCollectionFilters::class)->strict(),
            'sort' => $p->object([
                'name' => $p->union([$p->literal('asc'), $p->literal('desc')])->nullable()->default(null),
            ], PetCollectionSort::class)->strict(),
            'items' => $p->array($this->getModelResponseSchema($request)),
            'count' => $p->int(),
            '_type' => $p->literal('petCollection')->default('petCollection'),
        ], PetCollectionResponse::class)->strict()->postParse(fn ($r) => $this->addCollectionLinks($r));
    }

    public function getModelRequestSchema(ServerRequestInterface $request): ObjectSchemaInterface
    {
        $p = $this->parser;

        return $p->object([
            'name' => $p->string()->minLength(1),
            'tag' => $p->string()->minLength(1)->nullable(),
        ], PetRequest::class)->strict(['id', 'createdAt', 'updatedAt', '_type', '_links']);
    }

    public function getModelResponseSchema(ServerRequestInterface $request): ObjectSchemaInterface
    {
        $p = $this->parser;

        return $p->object([
            'id' => $p->string(),
            'createdAt' => $p->dateTime()->toString(),
            'updatedAt' => $p->dateTime()->nullable()->toString(),
            'name' => $p->string(),
            'tag' => $p->string()->nullable(),
            '_type' => $p->literal('pet')->default('pet'),
        ], PetResponse::class)->strict()->postParse(fn ($r) => $this->addModelLinks($r));
    }

    private function addCollectionLinks(PetCollectionResponse $response): PetCollectionResponse
    {
        $queryParams = [
            'offset' => $response->offset,
            'limit' => $response->limit,
            'filters' => $response->filters->jsonSerialize(),
            'sort' => $response->sort->jsonSerialize(),
        ];

        $response->_links = [
            'list' => $this->link($this->urlGenerator->generatePath('pet_list', [], $queryParams), 'GET'),
            'create' => $this->link($this->urlGenerator->generatePath('pet_create'), 'POST'),
        ];

        return $response;
    }

    private function addModelLinks(PetResponse $response): PetResponse
    {
        $response->_links = [
            'read' => $this->link($this->urlGenerator->generatePath('pet_read', ['id' => $response->id]), 'GET'),
            'update' => $this->link($this->urlGenerator->generatePath('pet_update', ['id' => $response->id]), 'PUT'),
            'delete' => $this->link($this->urlGenerator->generatePath('pet_delete', ['id' => $response->id]), 'DELETE'),
        ];

        return $response;
    }

    private function link(string $href, string $method): array
    {
        return ['href' => $href, 'templated' => false, 'rel' => [], 'attributes' => ['method' => $method]];
    }
}
```

### Repository

Implement `RepositoryInterface` for your persistence layer (Doctrine ORM, ODM, etc.).

```php
<?php

use Chubbyphp\Api\Collection\CollectionInterface;
use Chubbyphp\Api\Model\ModelInterface;
use Chubbyphp\Api\Repository\RepositoryInterface;

interface RepositoryInterface
{
    public function resolveCollection(CollectionInterface $collection): void;
    public function findById(string $id): ?ModelInterface;
    public function persist(ModelInterface $model): void;
    public function remove(ModelInterface $model): void;
    public function flush(): void;
}
```

### Request Handlers

The library provides PSR-15 request handlers for CRUD operations:

| Handler | Description |
|---------|-------------|
| `ListRequestHandler` | List collections with pagination, filtering, and sorting |
| `CreateRequestHandler` | Create new models (returns 201) |
| `ReadRequestHandler` | Read single models by ID |
| `UpdateRequestHandler` | Update existing models |
| `DeleteRequestHandler` | Delete models (returns 204) |

All handlers use content negotiation via `accept` and `contentType` request attributes.

## Copyright

2026 Dominik Zogg

[1]: https://packagist.org/packages/chubbyphp/chubbyphp-api
[2]: https://packagist.org/packages/chubbyphp/chubbyphp-decode-encode
[3]: https://packagist.org/packages/chubbyphp/chubbyphp-http-exception
[4]: https://packagist.org/packages/chubbyphp/chubbyphp-parsing
[5]: https://packagist.org/packages/psr/container
[6]: https://packagist.org/packages/psr/http-message
[7]: https://packagist.org/packages/psr/http-server-handler
[8]: https://packagist.org/packages/psr/http-server-middleware
[9]: https://packagist.org/packages/ramsey/uuid
