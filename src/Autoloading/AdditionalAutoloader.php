<?php

declare(strict_types=1);

namespace Rector\Autoloading;

use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\StaticReflection\DynamicSourceLocatorDecorator;
use Webmozart\Assert\Assert;

/**
 * Should it pass autoload files/directories to PHPStan analyzer?
 */
final readonly class AdditionalAutoloader
{
    public function __construct(
        private DynamicSourceLocatorDecorator $dynamicSourceLocatorDecorator
    ) {
    }

    public function autoloadFile(): void
    {
        if (! SimpleParameterProvider::hasParameter(Option::AUTOLOAD_FILE)) {
            return;
        }

        $autoloadFile = SimpleParameterProvider::provideStringParameter(Option::AUTOLOAD_FILE);
        if ($autoloadFile === '') {
            return;
        }

        Assert::fileExists($autoloadFile, sprintf('Extra autoload file %s was not found', $autoloadFile));

        require_once $autoloadFile;
    }

    public function autoloadPaths(): void
    {
        $autoloadPaths = SimpleParameterProvider::provideArrayParameter(Option::AUTOLOAD_PATHS);
        $autoloadPaths = $this->dynamicSourceLocatorDecorator->addPaths($autoloadPaths);

        // set values of Option::AUTOLOAD_PATHS with transformed paths
        SimpleParameterProvider::setParameter(Option::AUTOLOAD_PATHS, $autoloadPaths);
    }
}
