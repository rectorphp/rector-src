<?php

declare(strict_types=1);

namespace Rector\Compiler\PhpScoper;

final class StaticEasyPrefixer
{
    /**
     * @var string[]
     */
    final public const EXCLUDED_CLASSES = [
        // part of public interface of configs.php
        'Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator',
        // for SmartFileInfo
        'Symplify\SmartFileSystem\SmartFileInfo',
        // for ComposerJson because it is part of the public API. I.e. ComposerRectorInterface
        'Symplify\ComposerJsonManipulator\ValueObject\ComposerJson',
        // for usage in Helmich\TypoScriptParser\Parser\Traverser\Visitor
        'Helmich\TypoScriptParser\Parser\AST\Statement',
        'Helmich\TypoScriptParser\Parser\Traverser\Traverser',
        // for usage in packages/Testing/PHPUnit/PlatformAgnosticAssertions.php
        'PHPUnit\Framework\Constraint\IsEqual',
    ];

    /**
     * @return string[]
     */
    public static function getExcludedNamespacesAndClasses(): array
    {
        return array_merge(self::EXCLUDED_NAMESPACES, self::EXCLUDED_CLASSES);
    }
}
