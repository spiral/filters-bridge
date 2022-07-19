<?php

declare(strict_types=1);

namespace Spiral\Tests\Filters\Fixtures;

use Spiral\Filters\Filter;

class ArrayFilter extends Filter
{
    public const SCHEMA = [
        'tests' => [TestFilter::class]
    ];
}
