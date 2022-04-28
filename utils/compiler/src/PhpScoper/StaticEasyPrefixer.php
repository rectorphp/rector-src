<?php

declare(strict_types=1);

namespace Rector\Compiler\PhpScoper;

use Symplify\PackageBuilder\Parameter\ParameterProvider;

final class StaticEasyPrefixer
{
    /**
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
     * @var class-string<ParameterProvider>[]|string[]
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
        'Symplify\PackageBuilder\Parameter\ParameterProvider',
    ];

    /**
     * @return string[]
     */
    public static function getExcludedNamespacesAndClasses(): array
    {
        return array_merge(self::EXCLUDED_NAMESPACES, self::EXCLUDED_CLASSES);
    }
}
