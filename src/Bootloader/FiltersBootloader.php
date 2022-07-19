<?php

declare(strict_types=1);

namespace Spiral\Filters\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Core\Container;
use Spiral\Core\Container\InjectorInterface;
use Spiral\Core\FactoryInterface;
use Spiral\Filters\FilterInterface;
use Spiral\Filters\FilterProvider;
use Spiral\Filters\FilterProviderInterface;
use Spiral\Filters\InputInterface;
use Spiral\Validator\Bootloader\ValidatorBootloader;
use Spiral\Validator\Validation;

final class FiltersBootloader extends Bootloader implements InjectorInterface
{
    protected const DEPENDENCIES = [
        ValidatorBootloader::class
    ];

    protected const SINGLETONS = [
        FilterProviderInterface::class => [self::class, 'initFilterProvider']
    ];

    public function __construct(
        private readonly Container $container
    ) {
    }

    /**
     * Declare Filter injection.
     */
    public function init(): void
    {
        $this->container->bindInjector(FilterInterface::class, self::class);
    }

    public function createInjection(\ReflectionClass $class, string $context = null): object
    {
        return $this->container->get(FilterProviderInterface::class)->createFilter(
            $class->getName(),
            $this->container->get(InputInterface::class)
        );
    }

    private function initFilterProvider(
        Validation $validation,
        FactoryInterface $factory
    ): FilterProvider {
        return new FilterProvider(
            $validation,
            $factory
        );
    }
}
