<?php

declare(strict_types=1);

namespace Rector\Core\Autoloading;

use Rector\Core\Configuration\Option;
use Rector\Core\StaticReflection\DynamicSourceLocatorDecorator;
use Rector\Core\ValueObject\Configuration;
use Symfony\Component\Console\Input\InputInterface;
use Symplify\PackageBuilder\Parameter\ParameterProvider;
use Symplify\SmartFileSystem\FileSystemGuard;

/**
 * Should it pass autoload files/directories to PHPStan analyzer?
 */
final class AdditionalAutoloader
{
    public function __construct(
        private FileSystemGuard $fileSystemGuard,
        private ParameterProvider $parameterProvider,
        private DynamicSourceLocatorDecorator $dynamicSourceLocatorDecorator
    ) {
    }

    public function autoloadInput(InputInterface $input): void
    {
        if (! $input->hasOption(Option::AUTOLOAD_FILE)) {
            return;
        }

        /** @var string|null $autoloadFile */
        $autoloadFile = $input->getOption(Option::AUTOLOAD_FILE);
        if ($autoloadFile === null) {
            return;
        }

        $this->fileSystemGuard->ensureFileExists($autoloadFile, 'Extra autoload');
        require_once $autoloadFile;
    }

    public function autoloadPaths(Configuration $configuration): void
    {
        $autoloadPaths = $this->parameterProvider->provideArrayParameter(Option::AUTOLOAD_PATHS);
<<<<<<< HEAD
        $this->dynamicSourceLocatorDecorator->addPaths($autoloadPaths);
=======
        if ($autoloadPaths === []) {
            return;
        }

        $this->dynamicSourceLocatorDecorator->addPaths($autoloadPaths, $configuration);
>>>>>>> 8a154b63e (cleanup)
    }
}
