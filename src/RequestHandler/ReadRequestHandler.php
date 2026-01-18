<?php

declare(strict_types=1);

namespace Chubbyphp\Api\RequestHandler;

use Chubbyphp\Api\Dto\Model\ModelResponseInterface;
use Chubbyphp\Api\Parsing\ParsingInterface;
use Chubbyphp\Api\Repository\RepositoryInterface;
use Chubbyphp\DecodeEncode\Encoder\EncoderInterface;
use Chubbyphp\HttpException\HttpException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Uuid;

final class ReadRequestHandler implements RequestHandlerInterface
{
    public function __construct(
        private readonly ParsingInterface $parsing,
        private readonly RepositoryInterface $repository,
        private readonly EncoderInterface $encoder,
        private readonly ResponseFactoryInterface $responseFactory,
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var string $id */
        $id = $request->getAttribute('id');

        /** @var string $accept */
        $accept = $request->getAttribute('accept');

        if (!Uuid::isValid($id) || null === $model = $this->repository->findById($id)) {
            throw HttpException::createNotFound();
        }

        /** @var ModelResponseInterface $modelResponse */
        $modelResponse = $this->parsing->getModelResponseSchema($request)->parse($model);

        $output = $this->encoder->encode($modelResponse->jsonSerialize(), $accept);

        $response = $this->responseFactory->createResponse(200)->withHeader('Content-Type', $accept);
        $response->getBody()->write($output);

        return $response;
    }
}
