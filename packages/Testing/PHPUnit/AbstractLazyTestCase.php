<?php

declare(strict_types=1);

namespace Rector\Testing\PHPUnit;

use Illuminate\Container\Container;
use PHPStan\PhpDoc\TypeNodeResolver;
use PHPStan\Reflection\ReflectionProvider;
use PHPUnit\Framework\TestCase;
use Rector\BetterPhpDocParser\Contract\BasePhpDocNodeVisitorInterface;
use Rector\BetterPhpDocParser\DataProvider\CurrentTokenIteratorProvider;
use Rector\BetterPhpDocParser\PhpDocNodeMapper;
use Rector\BetterPhpDocParser\PhpDocNodeVisitor\ArrayTypePhpDocNodeVisitor;
use Rector\BetterPhpDocParser\PhpDocNodeVisitor\CallableTypePhpDocNodeVisitor;
use Rector\BetterPhpDocParser\PhpDocNodeVisitor\IntersectionTypeNodePhpDocNodeVisitor;
use Rector\BetterPhpDocParser\PhpDocNodeVisitor\TemplatePhpDocNodeVisitor;
use Rector\BetterPhpDocParser\PhpDocNodeVisitor\UnionTypeNodePhpDocNodeVisitor;
use Rector\Core\Configuration\Option;
use Rector\Core\Configuration\Parameter\ParameterProvider;
use Rector\NodeTypeResolver\DependencyInjection\BleedingEdgeIncludePurifier;
use Rector\NodeTypeResolver\DependencyInjection\PHPStanExtensionsConfigResolver;
use Rector\NodeTypeResolver\DependencyInjection\PHPStanServicesFactory;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeVisitor\CloningPhpDocNodeVisitor;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeVisitor\ParentConnectingPhpDocNodeVisitor;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\PHPStanStaticTypeMapper\PHPStanStaticTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\AccessoryLiteralStringTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\AccessoryNonEmptyStringTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\AccessoryNonFalsyStringTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\AccessoryNumericStringTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\ArrayTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\BooleanTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\CallableTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\ClassStringTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\ClosureTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\ConditionalTypeForParameterMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\FloatTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\GenericClassStringTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\HasMethodTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\HasOffsetTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\HasOffsetValueTypeTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\HasPropertyTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\IntegerTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\IterableTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\MixedTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\NeverTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\NonEmptyArrayTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\NullTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\ObjectTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\ObjectWithoutClassTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\OversizedArrayTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\ParentStaticTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\ResourceTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\SelfObjectTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\StrictMixedTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\StringTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\ThisTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\TypeWithClassNameTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\VoidTypeMapper;
use Rector\StaticTypeMapper\Contract\PhpDocParser\PhpDocTypeMapperInterface;
use Rector\StaticTypeMapper\Contract\PhpParser\PhpParserNodeMapperInterface;
use Rector\StaticTypeMapper\Mapper\PhpParserNodeMapper;
use Rector\StaticTypeMapper\PhpDoc\PhpDocTypeMapper;
use Rector\StaticTypeMapper\PhpDocParser\IdentifierTypeMapper;
use Rector\StaticTypeMapper\PhpDocParser\IntersectionTypeMapper;
use Rector\StaticTypeMapper\PhpDocParser\NullableTypeMapper;
use Rector\StaticTypeMapper\PhpDocParser\UnionTypeMapper;
use Rector\StaticTypeMapper\PhpParser\ExprNodeMapper;
use Rector\StaticTypeMapper\PhpParser\FullyQualifiedNodeMapper;
use Rector\StaticTypeMapper\PhpParser\IdentifierNodeMapper;
use Rector\StaticTypeMapper\PhpParser\IntersectionTypeNodeMapper;
use Rector\StaticTypeMapper\PhpParser\NameNodeMapper;
use Rector\StaticTypeMapper\PhpParser\NullableTypeNodeMapper;
use Rector\StaticTypeMapper\PhpParser\StringNodeMapper;
use Rector\StaticTypeMapper\PhpParser\UnionTypeNodeMapper;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

abstract class AbstractLazyTestCase extends TestCase
{
    /**
     * @var array<class-string<TypeMapperInterface>>
     */
    private const TYPE_MAPPER_CLASSES = [
        AccessoryLiteralStringTypeMapper::class,
        AccessoryNonEmptyStringTypeMapper::class,
        AccessoryNonFalsyStringTypeMapper::class,
        AccessoryNumericStringTypeMapper::class,
        ArrayTypeMapper::class,
        BooleanTypeMapper::class,
        CallableTypeMapper::class,
        ClassStringTypeMapper::class,
        ClosureTypeMapper::class,
        ConditionalTypeForParameterMapper::class,
        FloatTypeMapper::class,
        GenericClassStringTypeMapper::class,
        HasMethodTypeMapper::class,
        HasOffsetTypeMapper::class,
        HasOffsetValueTypeTypeMapper::class,
        HasPropertyTypeMapper::class,
        IntegerTypeMapper::class,
        IntersectionTypeMapper::class,
        IterableTypeMapper::class,
        MixedTypeMapper::class,
        NeverTypeMapper::class,
        NonEmptyArrayTypeMapper::class,
        NullTypeMapper::class,
        ObjectTypeMapper::class,
        ObjectWithoutClassTypeMapper::class,
        OversizedArrayTypeMapper::class,
        ParentStaticTypeMapper::class,
        ResourceTypeMapper::class,
        SelfObjectTypeMapper::class,
        StaticTypeMapper::class,
        StrictMixedTypeMapper::class,
        StringTypeMapper::class,
        ThisTypeMapper::class,
        TypeWithClassNameTypeMapper::class,
        UnionTypeMapper::class,
        VoidTypeMapper::class,
    ];

    /**
     * @var array<class-string<PhpDocTypeMapperInterface>>
     */
    private const PHP_DOC_TYPE_MAPPER_CLASSES = [
        IdentifierTypeMapper::class,
        IntersectionTypeMapper::class,
        NullableTypeMapper::class,
        UnionTypeMapper::class,
    ];

    /**
     * @var array<class-string<PhpParserNodeMapperInterface>>
     */
    private const PHP_PARSER_NODE_MAPPER_CLASSES = [
        ExprNodeMapper::class,
        FullyQualifiedNodeMapper::class,
        IdentifierNodeMapper::class,
        IntersectionTypeNodeMapper::class,
        NameNodeMapper::class,
        NullableTypeNodeMapper::class,
        StringNodeMapper::class,
        UnionTypeNodeMapper::class,
    ];

    /**
     * @var array<class-string<BasePhpDocNodeVisitorInterface>>
     */
    private const BASE_PHP_DOC_NODE_VISITOR_CLASSES = [
        ArrayTypePhpDocNodeVisitor::class,
        CallableTypePhpDocNodeVisitor::class,
        IntersectionTypeNodePhpDocNodeVisitor::class,
        TemplatePhpDocNodeVisitor::class,
        UnionTypeNodePhpDocNodeVisitor::class,
    ];

    private Container $container;

    protected function setUp(): void
    {
        parent::setUp();

        // @todo extract to Laravel container
        $container = new Container();

        $container->singleton(PHPStanServicesFactory::class, static function (Container $container) {
            // @todo fix patch
            $parameterProvider = new ParameterProvider(new \Symfony\Component\DependencyInjection\Container(new ParameterBag([
                Option::CONTAINER_CACHE_DIRECTORY => sys_get_temp_dir(),
            ])));
            return new PHPStanServicesFactory(
                $parameterProvider,
                $container->get(PHPStanExtensionsConfigResolver::class),
                $container->get(BleedingEdgeIncludePurifier::class)
            );
        });
        $container->singleton(TypeNodeResolver::class, static function (Container $container): TypeNodeResolver {
            $phpstanServicesFactory = $container->get(PHPStanServicesFactory::class);
            return $phpstanServicesFactory->createTypeNodeResolver();
        });

        foreach (self::PHP_PARSER_NODE_MAPPER_CLASSES as $phpParserNodeMapperClass) {
            $container->singleton($phpParserNodeMapperClass);
            $container->tag($phpParserNodeMapperClass, PhpParserNodeMapperInterface::class);
        }
        $container->singleton(PhpParserNodeMapper::class, static function (Container $container) {
            $phpParserNodeMappers = $container->tagged(PhpParserNodeMapperInterface::class);
            return new PhpParserNodeMapper($phpParserNodeMappers);
        });

        foreach (self::PHP_DOC_TYPE_MAPPER_CLASSES as $phpDocTypeMapperClass) {
            $container->singleton($phpDocTypeMapperClass);
            $container->tag($phpDocTypeMapperClass, PhpDocTypeMapperInterface::class);
        }
        $container->singleton(PhpDocTypeMapper::class, static function (Container $container): PhpDocTypeMapper {
            $phpDocTypeMappers = $container->tagged(PhpDocTypeMapperInterface::class);
            return new PhpDocTypeMapper($phpDocTypeMappers, $container->get(TypeNodeResolver::class));
        });

        $container->singleton(
            PHPStanStaticTypeMapper::class,
            static function (Container $container): PHPStanStaticTypeMapper {
                $typeMappers = $container->tagged(TypeMapperInterface::class);
                return new PHPStanStaticTypeMapper($typeMappers);
            }
        );
        foreach (self::TYPE_MAPPER_CLASSES as $typeMapperClass) {
            $container->singleton($typeMapperClass);
            $container->tag($typeMapperClass, TypeMapperInterface::class);
        }

        $container->singleton(ReflectionProvider::class, static function (Container $container): ReflectionProvider {
            $phpstanServicesFactory = $container->get(PHPStanServicesFactory::class);
            return $phpstanServicesFactory->createReflectionProvider();
        });

        foreach (self::BASE_PHP_DOC_NODE_VISITOR_CLASSES as $basePhpDocNodeVisitorClass) {
            $container->singleton($basePhpDocNodeVisitorClass);
            $container->tag($basePhpDocNodeVisitorClass, BasePhpDocNodeVisitorInterface::class);
        }
        $container->singleton(PhpDocNodeMapper::class, static function (Container $container): PhpDocNodeMapper {
            $basePhpDocNodeVisitors = $container->tagged(BasePhpDocNodeVisitorInterface::class);

            return new PhpDocNodeMapper(
                $container->get(CurrentTokenIteratorProvider::class),
                $container->get(ParentConnectingPhpDocNodeVisitor::class),
                $container->get(CloningPhpDocNodeVisitor::class),
                $basePhpDocNodeVisitors
            );
        });

        $this->container = $container;
    }

    /**
     * @template TType as object
     * @param class-string<TType> $class
     * @return TType
     */
    protected function make(string $class): object
    {
        return $this->container->make($class);
    }
}
