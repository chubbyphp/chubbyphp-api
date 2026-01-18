<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Api\Unit\ServiceFactory\Parsing;

use Chubbyphp\Api\ServiceFactory\Parsing\ParserFactory;
use Chubbyphp\Parsing\ParserInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Api\ServiceFactory\Parsing\ParserFactory
 *
 * @internal
 */
final class ParserFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $factory = new ParserFactory();

        self::assertInstanceOf(ParserInterface::class, $factory());
    }
}
