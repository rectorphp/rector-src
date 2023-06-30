<?php

declare(strict_types=1);

namespace Rector\Core\Configuration;

use Rector\Core\Configuration\Parameter\ParameterProvider;

/**
 * Rector native configuration provider, to keep deprecated options hidden,
 * but also provide configuration that custom rules can check
 */
final class RectorConfigProvider
{
    public function shouldImportNames(): bool
    {
        return ParameterProvider::provideBoolParameter(Option::AUTO_IMPORT_NAMES);
    }

    public function shouldRemoveUnusedImports(): bool
    {
        return ParameterProvider::provideBoolParameter(Option::REMOVE_UNUSED_IMPORTS);
    }

    /**
     * @api symfony
     */
    public function getSymfonyContainerPhp(): string
    {
        return ParameterProvider::provideStringParameter(Option::SYMFONY_CONTAINER_PHP_PATH_PARAMETER);
    }

    /**
     * @api symfony
     */
    public function getSymfonyContainerXml(): string
    {
        return ParameterProvider::provideStringParameter(Option::SYMFONY_CONTAINER_XML_PATH_PARAMETER);
    }

    public function getIndentChar(): string
    {
        return ParameterProvider::provideStringParameter(Option::INDENT_CHAR, ' ');
    }

    public function getIndentSize(): int
    {
        return ParameterProvider::provideIntParameter(Option::INDENT_SIZE);
    }
}
