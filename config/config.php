<?php

declare(strict_types=1);

use OndraM\CiDetector\CiDetector;
<<<<<<< HEAD
use PhpParser\BuilderFactory;
use PhpParser\ConstExprEvaluator;
use PhpParser\Lexer;
use PhpParser\NodeFinder;
use PhpParser\NodeVisitor\CloningVisitor;
use PHPStan\Analyser\NodeScopeResolver;
use PHPStan\Analyser\ScopeFactory;
use PHPStan\File\FileHelper;
use PHPStan\Parser\Parser;
use PHPStan\PhpDoc\TypeNodeResolver;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TypeParser;
use PHPStan\Reflection\ReflectionProvider;
use Rector\BetterPhpDocParser\Contract\BasePhpDocNodeVisitorInterface;
use Rector\BetterPhpDocParser\Contract\PhpDocParser\PhpDocNodeDecoratorInterface;
use Rector\BetterPhpDocParser\PhpDocNodeMapper;
use Rector\BetterPhpDocParser\PhpDocParser\BetterPhpDocParser;
use Rector\BetterPhpDocParser\PhpDocParser\BetterTypeParser;
use Rector\Caching\Cache;
use Rector\Caching\CacheFactory;
=======
>>>>>>> a2066862e5 (fix order of cleanup, remove closures first)
use Rector\Caching\ValueObject\Storage\MemoryCacheStorage;
use Rector\Config\RectorConfig;
use Rector\Core\Bootstrap\ExtensionConfigResolver;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([]);
    $rectorConfig->skip([]);

    $rectorConfig->autoloadPaths([]);
    $rectorConfig->bootstrapFiles([]);
    $rectorConfig->parallel(120, 16, 20);

    // to avoid autoimporting out of the box
    $rectorConfig->importNames(false, false);
    $rectorConfig->removeUnusedImports(false);

    $rectorConfig->importShortClasses();
    $rectorConfig->indent(' ', 4);

    $rectorConfig->fileExtensions(['php']);

    $rectorConfig->cacheDirectory(sys_get_temp_dir() . '/rector_cached_files');
    $rectorConfig->containerCacheDirectory(sys_get_temp_dir());

    // use faster in-memory cache in CI.
    // CI always starts from scratch, therefore IO intensive caching is not worth it
    if ((new CiDetector())->isCiDetected()) {
        $rectorConfig->cacheClass(MemoryCacheStorage::class);
    }

    $extensionConfigResolver = new ExtensionConfigResolver();
    $extensionConfigFiles = $extensionConfigResolver->provide();
    foreach ($extensionConfigFiles as $extensionConfigFile) {
        $rectorConfig->import($extensionConfigFile);
    }
<<<<<<< HEAD

    $services->load('Rector\Core\\', __DIR__ . '/../src')
        ->exclude([
            __DIR__ . '/../src/Rector',
            __DIR__ . '/../src/Console/Style/RectorConsoleOutputStyle.php',
            __DIR__ . '/../src/Exception',
            __DIR__ . '/../src/DependencyInjection/CompilerPass',
            __DIR__ . '/../src/DependencyInjection/Loader',
            __DIR__ . '/../src/DependencyInjection/LazyContainerFactory.php',
            __DIR__ . '/../src/Kernel',
            __DIR__ . '/../src/ValueObject',
            __DIR__ . '/../src/Bootstrap',
            __DIR__ . '/../src/Enum',
            __DIR__ . '/../src/functions',
            __DIR__ . '/../src/PhpParser/Node/CustomNode',
            __DIR__ . '/../src/PhpParser/ValueObject',
            __DIR__ . '/../src/PHPStan/NodeVisitor',
            __DIR__ . '/../src/constants.php',
        ]);

    $services->set(ConsoleApplication::class)
        ->arg('$commands', tagged_iterator(Command::class));
    $services->alias(Application::class, ConsoleApplication::class);

    $services->set(SimpleCallableNodeTraverser::class);

    $services->set(BuilderFactory::class);
    $services->set(CloningVisitor::class);
    $services->set(NodeFinder::class);

    $services->set(Parser::class)
        ->factory([service(PHPStanServicesFactory::class), 'createPHPStanParser']);

    $services->set(Lexer::class)
        ->factory([service(PHPStanServicesFactory::class), 'createEmulativeLexer']);

    $services->set(InflectorFactory::class);
    $services->set(Inflector::class)
        ->factory([service(InflectorFactory::class), 'build']);

    $services->set(VersionParser::class);

    // console
    $services->set(SymfonyStyleFactory::class);

    $services->alias(RectorStyle::class, SymfonyStyle::class);
    $services->set(SymfonyStyle::class)
        ->factory([service(SymfonyStyleFactory::class), 'create']);

    $services->set(FileHelper::class)
        ->factory([service(PHPStanServicesFactory::class), 'createFileHelper']);

    $services->set(Cache::class)
        ->factory([service(CacheFactory::class), 'create']);

    // type resolving
    $services->set(IntermediateSourceLocator::class);
    $services->alias(TypeParser::class, BetterTypeParser::class);

    // PHPStan services
    $services->set(ReflectionProvider::class)
        ->factory([service(PHPStanServicesFactory::class), 'createReflectionProvider']);

    $services->set(NodeScopeResolver::class)
        ->factory([service(PHPStanServicesFactory::class), 'createNodeScopeResolver']);

    $services->set(ScopeFactory::class)
        ->factory([service(PHPStanServicesFactory::class), 'createScopeFactory']);

    $services->set(TypeNodeResolver::class)
        ->factory([service(PHPStanServicesFactory::class), 'createTypeNodeResolver']);

    $services->set(DynamicSourceLocatorProvider::class)
        ->factory([service(PHPStanServicesFactory::class), 'createDynamicSourceLocatorProvider']);

    // add commands optinally
    if (class_exists(MissingInSetCommand::class)) {
        $services->set(MissingInSetCommand::class);
        $services->set(OutsideAnySetCommand::class);
    }

    if (class_exists(InitRecipeCommand::class)) {
        $services->set(InitRecipeCommand::class);
        $services->set(GenerateCommand::class);
    }

    // phpdoc parser
    $services->set(SmartPhpParser::class)
        ->factory([service(SmartPhpParserFactory::class), 'create']);

    $services->set(ConstExprEvaluator::class);
    $services->set(NodeFinder::class);

    // phpdoc parser
    $services->set(PhpDocParser::class);
    $services->alias(PhpDocParser::class, BetterPhpDocParser::class);

    $services->set(\PHPStan\PhpDocParser\Lexer\Lexer::class);
    $services->set(TypeParser::class)
        ->arg('$usedAttributes', [
            'lines' => true,
            'indexes' => true,
        ]);
    $services->set(ConstExprParser::class)
        ->arg('$usedAttributes', [
            'lines' => true,
            'indexes' => true,
        ]);

    // tagged services
    $services->set(PhpDocNodeMapper::class)
        ->arg('$phpDocNodeVisitors', tagged_iterator(BasePhpDocNodeVisitorInterface::class));

    $services->set(BetterPhpDocParser::class)
        ->arg('$phpDocNodeDecorators', tagged_iterator(PhpDocNodeDecoratorInterface::class));

    $services->set(NodeTypeResolver::class)
        ->arg('$nodeTypeResolvers', tagged_iterator(NodeTypeResolverInterface::class));

    $services->set(PHPStanNodeScopeResolver::class)
        ->arg('$nodeVisitors', tagged_iterator(ScopeResolverNodeVisitorInterface::class));

    $services->set(PHPStanStaticTypeMapper::class)
        ->arg('$typeMappers', tagged_iterator(TypeMapperInterface::class));

    $services->set(PhpParserNodeMapper::class)
        ->arg('$phpParserNodeMappers', tagged_iterator(PhpParserNodeMapperInterface::class));

    $services->set(PhpDocTypeMapper::class)
        ->arg('$phpDocTypeMappers', tagged_iterator(PhpDocTypeMapperInterface::class));

    $services->set(ClassNameImportSkipper::class)
        ->arg('$classNameImportSkipVoters', tagged_iterator(ClassNameImportSkipVoterInterface::class));

    $services->set(ConfigInitializer::class)
        ->arg('$rectors', tagged_iterator(RectorInterface::class));

    $services->set(ListRulesCommand::class)
        ->arg('$rectors', tagged_iterator(RectorInterface::class));

    $services->set(OutputFormatterCollector::class)
        ->arg('$outputFormatters', tagged_iterator(OutputFormatterInterface::class));

    $services->set(RectorNodeTraverser::class)
        ->arg('$phpRectors', tagged_iterator(PhpRectorInterface::class));

    $services->set(NodeNameResolver::class)
        ->arg('$nodeNameResolvers', tagged_iterator(NodeNameResolverInterface::class));

    $services->set(ApplicationFileProcessor::class)
        ->arg('$fileProcessors', tagged_iterator(FileProcessorInterface::class));

    $services->set(FileFactory::class)
        ->arg('$fileProcessors', tagged_iterator(FileProcessorInterface::class));

    $services->set(AnnotationToAttributeMapper::class)
        ->arg('$annotationToAttributeMappers', tagged_iterator(AnnotationToAttributeMapperInterface::class));
=======
>>>>>>> a2066862e5 (fix order of cleanup, remove closures first)
};
