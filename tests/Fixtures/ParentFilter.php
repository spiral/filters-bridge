<?php

declare(strict_types=1);

namespace Spiral\Tests\Filters\Fixtures;

use Spiral\Filters\Filter;

class ParentFilter extends Filter
{
    public const SCHEMA = [
        'name' => 'name',
        'test' => TestFilter::class
    ];
}
