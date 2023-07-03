<?php

declare(strict_types=1);

namespace Rector\Core\Configuration;

use Rector\Core\Configuration\Parameter\ParameterProvider;
use Rector\Core\Configuration\Parameter\SimpleParameterProvider;

/**
 * Rector native configuration provider, to keep deprecated options hidden,
 * but also provide configuration that custom rules can check
 */
final class RectorConfigProvider
{
    public function __construct(
        private readonly ParameterProvider $parameterProvider
    ) {
    }

    public function shouldImportNames(): bool
    {
        return SimpleParameterProvider::provideBoolParameter(Option::AUTO_IMPORT_NAMES);
    }

    public function shouldRemoveUnusedImports(): bool
    {
        return SimpleParameterProvider::provideBoolParameter(Option::REMOVE_UNUSED_IMPORTS);
    }

    /**
     * @api symfony
     */
    public function getSymfonyContainerPhp(): string
    {
        return $this->parameterProvider->provideStringParameter(Option::SYMFONY_CONTAINER_PHP_PATH_PARAMETER);
    }

    /**
     * @api symfony
     */
    public function getSymfonyContainerXml(): string
    {
        return SimpleParameterProvider::provideStringParameter(Option::SYMFONY_CONTAINER_XML_PATH_PARAMETER);
    }

    public function getIndentChar(): string
    {
        return SimpleParameterProvider::provideStringParameter(Option::INDENT_CHAR, ' ');
    }

    public function getIndentSize(): int
    {
        return SimpleParameterProvider::provideIntParameter(Option::INDENT_SIZE);
    }
}
