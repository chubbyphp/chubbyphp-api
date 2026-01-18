<?php

declare(strict_types=1);

namespace Chubbyphp\Api\ServiceFactory\Parsing;

use Chubbyphp\Parsing\Parser;
use Chubbyphp\Parsing\ParserInterface;

final class ParserFactory
{
    public function __invoke(): ParserInterface
    {
        return new Parser();
    }
}
