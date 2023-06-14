<?php

declare(strict_types=1);

namespace Rector\Core\Kernel;

use Rector\BetterPhpDocParser\Contract\BasePhpDocNodeVisitorInterface;
use Rector\BetterPhpDocParser\Contract\PhpDocParser\PhpDocNodeDecoratorInterface;
use Rector\ChangesReporting\Contract\Output\OutputFormatterInterface;
use Rector\CodingStyle\Contract\ClassNameImport\ClassNameImportSkipVoterInterface;
use Rector\Core\Config\Loader\ConfigureCallMergingLoaderFactory;
use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\Contract\Rector\NonPhpRectorInterface;
use Rector\Core\Contract\Rector\PhpRectorInterface;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\NodeNameResolver\Contract\NodeNameResolverInterface;
use Rector\NodeTypeResolver\Contract\NodeTypeResolverInterface;
use Rector\NodeTypeResolver\PHPStan\Scope\Contract\NodeVisitor\ScopeResolverNodeVisitorInterface;
use Rector\PhpAttribute\Contract\AnnotationToAttributeMapperInterface;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\StaticTypeMapper\Contract\PhpDocParser\PhpDocTypeMapperInterface;
use Rector\StaticTypeMapper\Contract\PhpParser\PhpParserNodeMapperInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Webmozart\Assert\Assert;

final class ContainerBuilderFactory
{
    /**
     * @var array<class-string>
     */
    private const TYPES_TO_TAG_AUTOCONFIGURE = [
        BasePhpDocNodeVisitorInterface::class,
        PhpDocNodeDecoratorInterface::class,
        NodeTypeResolverInterface::class,
        ScopeResolverNodeVisitorInterface::class,
        TypeMapperInterface::class,
        PhpParserNodeMapperInterface::class,
        PhpDocTypeMapperInterface::class,
        ClassNameImportSkipVoterInterface::class,
        RectorInterface::class,
        RectorInterface::class,
        OutputFormatterInterface::class,
        NonPhpRectorInterface::class,
        PhpRectorInterface::class,
        NodeNameResolverInterface::class,
        FileProcessorInterface::class,
        AnnotationToAttributeMapperInterface::class,
    ];

    public function __construct(
        private readonly ConfigureCallMergingLoaderFactory $configureCallMergingLoaderFactory
    ) {
    }

    /**
     * @param string[] $configFiles
     * @param CompilerPassInterface[] $compilerPasses
     */
    public function create(array $configFiles, array $compilerPasses): ContainerBuilder
    {
        Assert::allIsAOf($compilerPasses, CompilerPassInterface::class);
        Assert::allString($configFiles);

        $containerBuilder = new ContainerBuilder();

        // tagged services here
        foreach (self::TYPES_TO_TAG_AUTOCONFIGURE as $typeToTagAutoconfigure) {
            $containerBuilder->registerForAutoconfiguration($typeToTagAutoconfigure)
                ->addTag($typeToTagAutoconfigure);
        }

        $this->registerConfigFiles($containerBuilder, $configFiles);
        foreach ($compilerPasses as $compilerPass) {
            $containerBuilder->addCompilerPass($compilerPass);
        }

        return $containerBuilder;
    }

    /**
     * @param string[] $configFiles
     */
    private function registerConfigFiles(ContainerBuilder $containerBuilder, array $configFiles): void
    {
        $delegatingLoader = $this->configureCallMergingLoaderFactory->create($containerBuilder, getcwd());
        foreach ($configFiles as $configFile) {
            $delegatingLoader->load($configFile);
        }
    }
}
