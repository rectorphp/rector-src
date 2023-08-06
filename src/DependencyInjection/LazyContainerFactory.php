<?php

declare(strict_types=1);

namespace Rector\Core\DependencyInjection;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\Rules\English\InflectorFactory;
use Illuminate\Container\Container;
use PhpParser\Lexer;
use PHPStan\Parser\Parser;
use PHPStan\PhpDoc\TypeNodeResolver;
use PHPStan\Reflection\ReflectionProvider;
use Rector\BetterPhpDocParser\Contract\BasePhpDocNodeVisitorInterface;
use Rector\BetterPhpDocParser\Contract\PhpDocParser\PhpDocNodeDecoratorInterface;
use Rector\BetterPhpDocParser\PhpDocNodeMapper;
use Rector\BetterPhpDocParser\PhpDocParser\BetterPhpDocParser;
use Rector\Caching\Cache;
use Rector\Caching\CacheFactory;
use Rector\Core\Configuration\Option;
use Rector\Core\Configuration\Parameter\SimpleParameterProvider;
use Rector\NodeNameResolver\Contract\NodeNameResolverInterface;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Contract\NodeTypeResolverInterface;
use Rector\NodeTypeResolver\DependencyInjection\PHPStanServicesFactory;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\PHPStanStaticTypeMapper\PHPStanStaticTypeMapper;
use Rector\StaticTypeMapper\Contract\PhpDocParser\PhpDocTypeMapperInterface;
use Rector\StaticTypeMapper\Contract\PhpParser\PhpParserNodeMapperInterface;
use Rector\StaticTypeMapper\Mapper\PhpParserNodeMapper;
use Rector\StaticTypeMapper\PhpDoc\PhpDocTypeMapper;

final class LazyContainerFactory
{
    /**
     * @var array<class-string<NodeNameResolverInterface>>
     */
    private const NODE_NAME_RESOLVER_CLASSES = [
        NodeNameResolver\ClassConstFetchNameResolver::class,
        NodeNameResolver\ClassConstNameResolver::class,
        NodeNameResolver\ClassNameResolver::class,
        NodeNameResolver\EmptyNameResolver::class,
        NodeNameResolver\FuncCallNameResolver::class,
        NodeNameResolver\FunctionNameResolver::class,
        NodeNameResolver\NameNameResolver::class,
        NodeNameResolver\ParamNameResolver::class,
        NodeNameResolver\PropertyNameResolver::class,
        NodeNameResolver\UseNameResolver::class,
        NodeNameResolver\VariableNameResolver::class,
    ];

    /**
     * @api used as next container factory
     */
    public function create(): Container
    {
        $container = new Container();

        // setup base parameters - from RectorConfig
        SimpleParameterProvider::setParameter(Option::CACHE_DIR, sys_get_temp_dir() . '/rector_cached_files');
        SimpleParameterProvider::setParameter(Option::CONTAINER_CACHE_DIRECTORY, sys_get_temp_dir());

        $container->singleton(Inflector::class, static function (): Inflector {
            $inflectorFactory = new InflectorFactory();
            return $inflectorFactory->build();
        });

        // caching
        $container->singleton(Cache::class, static function (Container $container): Cache {
            /** @var CacheFactory $cacheFactory */
            $cacheFactory = $container->make(CacheFactory::class);
            return $cacheFactory->create();
        });

        // tagged services
        $container->when(BetterPhpDocParser::class)
            ->needs('$phpDocNodeDecorators')
            ->giveTagged(PhpDocNodeDecoratorInterface::class);

        $container->when(PHPStanStaticTypeMapper::class)
            ->needs('$typeMappers')
            ->giveTagged(TypeMapperInterface::class);

        $container->when(PhpDocTypeMapper::class)
            ->needs('$phpDocTypeMappers')
            ->giveTagged(PhpDocTypeMapperInterface::class);

        $container->when(PhpParserNodeMapper::class)
            ->needs('$phpParserNodeMappers')
            ->giveTagged(PhpParserNodeMapperInterface::class);

        $container->when(NodeTypeResolver::class)
            ->needs('$nodeTypeResolvers')
            ->giveTagged(NodeTypeResolverInterface::class);

        // node name resolvers
        $container->when(NodeNameResolver::class)
            ->needs('$nodeNameResolvers')
            ->giveTagged(NodeNameResolverInterface::class);

        foreach (self::NODE_NAME_RESOLVER_CLASSES as $nodeNameResolverClass) {
            $container->singleton($nodeNameResolverClass);
            $container->tag($nodeNameResolverClass, NodeNameResolverInterface::class);
        }

        $container->singleton(Parser::class, function (Container $container) {
            $phpstanServiceFactory = $container->make(PHPStanServicesFactory::class);
            return $phpstanServiceFactory->createPHPStanParser();
        });

        // phpstan factory
        $container->singleton(
            \PHPStan\Reflection\ReflectionProvider::class,
            function (Container $container): ReflectionProvider {
                $phpstanServiceFactory = $container->make(PHPStanServicesFactory::class);
                return $phpstanServiceFactory->createReflectionProvider();
            }
        );

        $container->singleton(Parser::class, function (Container $container) {
            $phpstanServiceFactory = $container->make(PHPStanServicesFactory::class);
            return $phpstanServiceFactory->createPHPStanParser();
        });

        $container->singleton(Lexer::class, function (Container $container) {
            $phpstanServiceFactory = $container->make(PHPStanServicesFactory::class);
            return $phpstanServiceFactory->createEmulativeLexer();
        });

        $container->singleton(TypeNodeResolver::class, function (Container $container) {
            $phpstanServiceFactory = $container->make(PHPStanServicesFactory::class);
            return $phpstanServiceFactory->createTypeNodeResolver();
        });

        // @todo add base node visitors
        $container->singleton(PhpDocNodeMapper::class, PhpDocNodeMapper::class);
        $container->when(PhpDocNodeMapper::class)
            ->needs('$phpDocNodeVisitors')
            ->giveTagged(BasePhpDocNodeVisitorInterface::class);

        return $container;
    }
}
