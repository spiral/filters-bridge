<?php

declare(strict_types=1);

namespace Spiral\Tests\Filters;

use Spiral\Filters\ArrayInput;
use Spiral\Tests\Filters\Fixtures\ParentPathFilter;

class ParentPathTest extends BaseTest
{
    public function testChildrenValid(): void
    {
        $filter = $this->getProvider()->createFilter(ParentPathFilter::class, new ArrayInput([
            'custom' => [
                'id' => 'value'
            ]
        ]));

        $this->assertTrue($filter->isValid());
        $this->assertSame('value', $filter->test->id);
    }

//    public function testChildrenInvalid(): void
//    {
//        $filter = $this->getProvider()->createFilter(ParentPathFilter::class, new ArrayInput([]));
//
//        $this->assertFalse($filter->isValid());
//        $this->assertSame([
//            'custom' => [
//                'id' => 'This value is required.'
//            ]
//        ], $filter->getErrors());
//    }
}
