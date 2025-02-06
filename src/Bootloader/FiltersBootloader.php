<?php

declare(strict_types=1);

namespace Spiral\Filters\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Console\Bootloader\ConsoleBootloader;
use Spiral\Core\Container;
use Spiral\Core\Container\InjectorInterface;
use Spiral\Core\FactoryInterface;
use Spiral\Filters\Command\FilterCommand;
use Spiral\Filters\FilterInterface;
use Spiral\Filters\FilterProvider;
use Spiral\Filters\FilterProviderInterface;
use Spiral\Filters\InputInterface;
use Spiral\Filters\Scaffolder\Declaration\FilterDeclaration;
use Spiral\Scaffolder\Bootloader\ScaffolderBootloader;
use Spiral\Validator\Bootloader\ValidatorBootloader;
use Spiral\Validator\Validation;

final class FiltersBootloader extends Bootloader implements InjectorInterface
{
    protected const DEPENDENCIES = [
        ValidatorBootloader::class,
        ConsoleBootloader::class,
        ScaffolderBootloader::class,
    ];

    protected const SINGLETONS = [
        FilterProviderInterface::class => [self::class, 'initFilterProvider'],
    ];

    public function __construct(
        private readonly Container $container
    ) {
    }

    /**
     * Declare Filter injection.
     */
    public function init(ConsoleBootloader $console, ScaffolderBootloader $scaffolder): void
    {
        $this->container->bindInjector(FilterInterface::class, self::class);

        $this->registerScaffolderDeclaration($scaffolder);
        $this->registerCommands($console);
    }

    public function createInjection(\ReflectionClass $class, ?string $context = null): object
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

    private function registerScaffolderDeclaration(ScaffolderBootloader $scaffolder): void
    {
        $scaffolder->addDeclaration(FilterDeclaration::TYPE, [
            'namespace' => 'Request',
            'postfix' => 'Request',
            'class' => FilterDeclaration::class,
            'options' => [
                //Set of default filters and validate rules for various types
                'mapping' => [
                    'int' => [
                        'source' => 'data',
                        'setter' => 'intval',
                        'validates' => ['notEmpty', 'integer'],
                    ],
                    'integer' => [
                        'source' => 'data',
                        'setter' => 'intval',
                        'validates' => ['notEmpty', 'integer'],
                    ],
                    'float' => [
                        'source' => 'data',
                        'setter' => 'floatval',
                        'validates' => ['notEmpty', 'float'],
                    ],
                    'double' => [
                        'source' => 'data',
                        'setter' => 'floatval',
                        'validates' => ['notEmpty', 'float'],
                    ],
                    'string' => [
                        'source' => 'data',
                        'setter' => 'strval',
                        'validates' => ['notEmpty', 'string'],
                    ],
                    'bool' => [
                        'source' => 'data',
                        'setter' => 'boolval',
                        'validates' => ['notEmpty', 'boolean'],
                    ],
                    'boolean' => [
                        'source' => 'data',
                        'setter' => 'boolval',
                        'validates' => ['notEmpty', 'boolean'],
                    ],
                    'email' => [
                        'source' => 'data',
                        'setter' => 'strval',
                        'validates' => ['notEmpty', 'string', 'email'],
                    ],
                    'file' => [
                        'source' => 'file',
                        'validates' => ['file::uploaded'],
                    ],
                    'image' => [
                        'source' => 'file',
                        'validates' => ['image::uploaded', 'image::valid'],
                    ],
                    null => [
                        'source' => 'data',
                        'setter' => 'strval',
                        'validates' => ['notEmpty', 'string'],
                    ],
                ],
            ],
        ]);
    }

    private function registerCommands(ConsoleBootloader $console): void
    {
        $console->addCommand(FilterCommand::class);
    }
}
