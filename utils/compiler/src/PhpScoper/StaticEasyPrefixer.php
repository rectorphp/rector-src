<?php

declare(strict_types=1);

namespace Rector\Compiler\PhpScoper;

final class StaticEasyPrefixer
{
    /**ParameterP
     * @var string[]
     */
    public const EXCLUDED_CLASSES = [
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
     * @var string[]
     */
    private const EXCLUDED_NAMESPACES = [
        // naturally
        'Rector\*',
        // we use this API a lot
        'PhpParser\*',
        'Ssch\TYPO3Rector\*',

        // phpstan needs to be here, as phpstan/vendor autoload is statically generated and namespaces cannot be changed
        'PHPStan\*',

        // this is public API of a Rector rule
        'Symplify\RuleDocGenerator\*',
    ];

    /**
     * @return string[]
     */
    public static function getExcludedNamespacesAndClasses(): array
    {
        return array_merge(self::EXCLUDED_NAMESPACES, self::EXCLUDED_CLASSES);
    }
}
