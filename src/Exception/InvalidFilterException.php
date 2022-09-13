<?php

declare(strict_types=1);

namespace Spiral\Filters\Exception;

use Spiral\Core\Exception\ControllerException;
use Spiral\Filters\FilterInterface;

final class InvalidFilterException extends ControllerException
{
    private array $errors = [];

    public function __construct(FilterInterface $filter)
    {
        $this->errors = $filter->getErrors();

        parent::__construct(\sprintf('Invalid `%s`', $filter::class), self::BAD_ARGUMENT);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
