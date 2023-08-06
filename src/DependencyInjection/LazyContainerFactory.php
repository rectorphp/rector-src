<?php

declare(strict_types=1);

namespace Rector\Core\DependencyInjection;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\Rules\English\InflectorFactory;
use Illuminate\Container\Container;
use PhpParser\Lexer;
use PHPStan\Parser\Parser;
use PHPStan\PhpDoc\TypeNodeResolver;
use Rector\BetterPhpDocParser\Contract\BasePhpDocNodeVisitorInterface;
use Rector\BetterPhpDocParser\Contract\PhpDocParser\PhpDocNodeDecoratorInterface;
use Rector\BetterPhpDocParser\PhpDocNodeMapper;
use Rector\BetterPhpDocParser\PhpDocParser\BetterPhpDocParser;
use Rector\Caching\Cache;
use Rector\Caching\CacheFactory;
use Rector\Core\Configuration\CurrentNodeProvider;
use Rector\Core\Configuration\Option;
use Rector\Core\Configuration\Parameter\SimpleParameterProvider;
use Rector\NodeTypeResolver\DependencyInjection\PHPStanExtensionsConfigResolver;
use Rector\NodeTypeResolver\DependencyInjection\PHPStanServicesFactory;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\PHPStanStaticTypeMapper\PHPStanStaticTypeMapper;
use Rector\StaticTypeMapper\Contract\PhpDocParser\PhpDocTypeMapperInterface;
use Rector\StaticTypeMapper\Mapper\PhpParserNodeMapper;
use Rector\StaticTypeMapper\PhpDoc\PhpDocTypeMapper;

final class LazyContainerFactory
{
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

        // php doc node parser
        $container->singleton(BetterPhpDocParser::class, BetterPhpDocParser::class);
        $container->when(BetterPhpDocParser::class)
            ->needs('$phpDocNodeDecorators')
            ->giveTagged(PhpDocNodeDecoratorInterface::class);

        $container->singleton(PHPStanStaticTypeMapper::class, PHPStanStaticTypeMapper::class);
        $container->when(PHPStanStaticTypeMapper::class)
            ->needs('$typeMappers')
            ->giveTagged(TypeMapperInterface::class);

        $container->singleton(PhpDocTypeMapper::class, PhpDocTypeMapper::class);
        $container->when(PhpDocTypeMapper::class)
            ->needs('$phpDocTypeMappers')
            ->giveTagged(PhpDocTypeMapperInterface::class);

        $container->singleton(PhpParserNodeMapper::class, PhpParserNodeMapper::class);
        $container->when(PhpParserNodeMapper::class)
            ->needs('$phpParserNodeMappers')
            ->giveTagged(\Rector\StaticTypeMapper\Contract\PhpParser\PhpParserNodeMapperInterface::class);

        $container->singleton(Parser::class, function (Container $container) {
            $phpstanServiceFactory = $container->make(PHPStanServicesFactory::class);
            return $phpstanServiceFactory->createPHPStanParser();
        });

        // phpstan factory
        $container->singleton(CurrentNodeProvider::class, CurrentNodeProvider::class);

        $container->singleton(PHPStanExtensionsConfigResolver::class, PHPStanExtensionsConfigResolver::class);
        $container->singleton(PHPStanServicesFactory::class, PHPStanServicesFactory::class);

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
