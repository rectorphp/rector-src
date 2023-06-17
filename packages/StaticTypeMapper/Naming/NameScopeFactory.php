<?php

declare(strict_types=1);

namespace Rector\StaticTypeMapper\Naming;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;
use PHPStan\Analyser\NameScope;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\Generic\TemplateTypeMap;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Core\PhpParser\ClassLikeAstResolver;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\Naming\Naming\UseImportsResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @see https://github.com/phpstan/phpstan-src/blob/8376548f76e2c845ae047e3010e873015b796818/src/Analyser/NameScope.php#L32
 */
final class NameScopeFactory
{
    private StaticTypeMapper $staticTypeMapper;

    private PhpDocInfoFactory $phpDocInfoFactory;

    private UseImportsResolver $useImportsResolver;

    private ReflectionResolver $reflectionResolver;

    private ClassLikeAstResolver $classLikeAstResolver;

    // This is needed to avoid circular references

    #[Required]
    public function autowire(
        PhpDocInfoFactory $phpDocInfoFactory,
        StaticTypeMapper $staticTypeMapper,
        BetterNodeFinder $betterNodeFinder,
        UseImportsResolver $useImportsResolver,
        ReflectionResolver $reflectionResolver,
        ClassLikeAstResolver $classLikeAstResolver
    ): void {
        $this->phpDocInfoFactory = $phpDocInfoFactory;
        $this->staticTypeMapper = $staticTypeMapper;
        $this->useImportsResolver = $useImportsResolver;
        $this->reflectionResolver = $reflectionResolver;
        $this->classLikeAstResolver = $classLikeAstResolver;
    }

    public function createNameScopeFromNodeWithoutTemplateTypes(Node $node): NameScope
    {
        $scope = $node->getAttribute(AttributeKey::SCOPE);
        $namespace = $scope instanceof Scope ? $scope->getNamespace() : null;

        $uses = $this->useImportsResolver->resolveForNode($node);
        $usesAliasesToNames = $this->resolveUseNamesByAlias($uses);

        if ($scope instanceof Scope && $scope->getClassReflection() instanceof ClassReflection) {
            $classReflection = $scope->getClassReflection();
            $className = $classReflection->getName();
        } else {
            $className = null;
        }

        return new NameScope($namespace, $usesAliasesToNames, $className);
    }

    public function createNameScopeFromNode(Node $node): NameScope
    {
        $nameScope = $this->createNameScopeFromNodeWithoutTemplateTypes($node);
        $templateTypeMap = $this->templateTemplateTypeMap($node);

        return new NameScope(
            $nameScope->getNamespace(),
            $nameScope->getUses(),
            $nameScope->getClassName(),
            null,
            $templateTypeMap
        );
    }

    /**
     * @param Use_[]|GroupUse[] $useNodes
     * @return array<string, string>
     */
    private function resolveUseNamesByAlias(array $useNodes): array
    {
        $useNamesByAlias = [];

        foreach ($useNodes as $useNode) {
            $prefix = $this->useImportsResolver->resolvePrefix($useNode);
            foreach ($useNode->uses as $useUse) {
                /** @var UseUse $useUse */
                $aliasName = $useUse->getAlias()
                    ->name;

                // uses must be lowercase, as PHPStan lowercases it
                $lowercasedAliasName = strtolower($aliasName);

                $useNamesByAlias[$lowercasedAliasName] = $prefix . $useUse->name->toString();
            }
        }

        return $useNamesByAlias;
    }

    private function templateTemplateTypeMap(Node $node): TemplateTypeMap
    {
        $nodeTemplateTypes = $this->resolveTemplateTypesFromNode($node);

        $classTemplateTypes = [];

        $classReflection = $this->reflectionResolver->resolveClassReflection($node);
        if ($classReflection instanceof ClassReflection) {
            $classLike = $this->classLikeAstResolver->resolveClassFromClassReflection($classReflection);

            if ($classLike instanceof ClassLike) {
                $classTemplateTypes = $this->resolveTemplateTypesFromNode($classLike);
            }
        }

        $templateTypes = array_merge($nodeTemplateTypes, $classTemplateTypes);
        return new TemplateTypeMap($templateTypes);
    }

    /**
     * @return Type[]
     */
    private function resolveTemplateTypesFromNode(Node $node): array
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);

        $templateTypes = [];

        foreach ($phpDocInfo->getTemplateTagValueNodes() as $templateTagValueNode) {
            $templateTypes[$templateTagValueNode->name] = $this->staticTypeMapper->mapPHPStanPhpDocTypeToPHPStanType(
                $templateTagValueNode,
                $node
            );
        }

        return $templateTypes;
    }
}
