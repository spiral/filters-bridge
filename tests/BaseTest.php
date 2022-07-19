<?php

declare(strict_types=1);

namespace Spiral\Tests\Filters;

use PHPUnit\Framework\TestCase;
use Spiral\Core\Container;
use Spiral\Filters\FilterProvider;
use Spiral\Validator\Checker\AddressChecker;
use Spiral\Validator\Checker\FileChecker;
use Spiral\Validator\Checker\ImageChecker;
use Spiral\Validator\Checker\StringChecker;
use Spiral\Validator\Checker\TypeChecker;
use Spiral\Validator\Config\ValidatorConfig;
use Spiral\Validator\ParserInterface;
use Spiral\Validator\RuleParser;
use Spiral\Validator\RulesInterface;
use Spiral\Validation\ValidationInterface;
use Spiral\Validation\ValidationProvider;
use Spiral\Validator\RulesProvider;
use Spiral\Validator\Validation;

abstract class BaseTest extends TestCase
{
    public const VALIDATION_CONFIG = [
        'checkers' => [
            'file' => FileChecker::class,
            'image' => ImageChecker::class,
            'type' => TypeChecker::class,
            'address' => AddressChecker::class,
            'string' => StringChecker::class,
        ],
        'aliases' => [
            'notEmpty' => 'type::notEmpty',
            'email' => 'address::email',
            'url' => 'address::url',
        ],
    ];

    protected Container $container;

    public function setUp(): void
    {
        $this->container = new Container();

        $this->container->bindSingleton(ValidationInterface::class, Validation::class);
        $this->container->bindSingleton(RulesInterface::class, RulesProvider::class);
        $this->container->bindSingleton(ParserInterface::class, RuleParser::class);

        $this->container->bind(
            ValidatorConfig::class,
            new ValidatorConfig(static::VALIDATION_CONFIG)
        );
    }

    protected function getProvider(): FilterProvider
    {
        return new FilterProvider(
            $this->container->get(ValidationInterface::class)
        );
    }
}
