<?php

declare(strict_types=1);

namespace Rector\Php81\NodeFactory;

use PhpParser\BuilderFactory;
use PhpParser\Node\Expr;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\Node\Stmt\EnumCase;
use PhpParser\Node\Stmt\Return_;
use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Core\PhpParser\Node\Value\ValueResolver;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class EnumFactory
{
    public function __construct(
        private readonly NodeNameResolver   $nodeNameResolver,
        private readonly PhpDocInfoFactory  $phpDocInfoFactory,
        private readonly BuilderFactory     $builderFactory,
        private readonly ValueResolver      $valueResolver
    )
    {
    }

    public function createFromClass(Class_ $class): Enum_
    {
        $shortClassName = $this->nodeNameResolver->getShortName($class);
        $enum = new Enum_($shortClassName, [], [
            'startLine' => $class->getStartLine(),
            'endLine' => $class->getEndLine(),
        ]);
        $enum->namespacedName = $class->namespacedName;

        $constants = $class->getConstants();

        $enum->stmts = $class->getTraitUses();

        if ($constants !== []) {
            $value = $this->valueResolver->getValue($constants[0]->consts[0]->value);
            $enum->scalarType = is_string($value)
                ? new Identifier('string')
                : new Identifier('int');

            // constant to cases
            foreach ($constants as $constant) {
                $enum->stmts[] = $this->createEnumCaseFromConst($constant);
            }
        }

        return $enum;
    }

    public function createFromSpatieClass(Class_ $class): Enum_
    {
        $shortClassName = $this->nodeNameResolver->getShortName($class);
        $enum = new Enum_($shortClassName, [], [
            'startLine' => $class->getStartLine(),
            'endLine' => $class->getEndLine(),
        ]);
        $enum->namespacedName = $class->namespacedName;

        // constant to cases
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($class);

        $docBlockMethods = $phpDocInfo->getTagsByName('@method');
        if ($docBlockMethods !== []) {
            $mapping = $this->generateMappingFromClass($class);
            $identifierType = $this->getIdentifierTypeFromMappings($mapping);
            $enum->scalarType = new Identifier($identifierType);

            foreach ($docBlockMethods as $docBlockMethod) {
                $enum->stmts[] = $this->createEnumCaseFromDocComment($docBlockMethod, $mapping);
            }
        }

        return $enum;
    }

    private function createEnumCaseFromConst(ClassConst $classConst): EnumCase
    {
        $constConst = $classConst->consts[0];
        $enumCase = new EnumCase($constConst->name, $constConst->value);

        // mirror comments
        $enumCase->setAttribute(AttributeKey::PHP_DOC_INFO, $classConst->getAttribute(AttributeKey::PHP_DOC_INFO));
        $enumCase->setAttribute(AttributeKey::COMMENTS, $classConst->getAttribute(AttributeKey::COMMENTS));

        return $enumCase;
    }

    /**
     * @param array<int|string, mixed> $mapping
     */
    private function createEnumCaseFromDocComment(PhpDocTagNode $phpDocTagNode, array $mapping = []): EnumCase
    {
        /** @var MethodTagValueNode $nodeValue */
        $nodeValue = $phpDocTagNode->value;
        $enumValue = $mapping[$nodeValue->methodName] ?? $nodeValue->methodName;
        $enumName = strtoupper($nodeValue->methodName);
        $enumExpr = $this->builderFactory->val($enumValue);

        return new EnumCase($enumName, $enumExpr);
    }

    /**
     * @return array<int|string, mixed>
     */
    private function generateMappingFromClass(Class_ $class): array
    {
        $mapping = [];

        $classMethod = $class->getMethod('values');
        if ($classMethod === null || !is_array($classMethod->stmts)) {
            return $mapping;
        }

        foreach ($classMethod->stmts as $methodStmt) {
            if (
                !($methodStmt instanceof Return_)
                || !($methodStmt->expr instanceof Expr\Array_)
            ) {
                continue;
            }
            foreach ($methodStmt->expr->items as $item) {
                if ($item instanceof Expr\ArrayItem
                    && ($item->key instanceof LNumber || $item->key instanceof String_)
                    && ($item->value instanceof LNumber || $item->value instanceof String_)
                ) {
                    $mapping[$item->key->value] = $item->value->value;
                }
            }
        }

        return $mapping;
    }

    /**
     * @param array<int|string, mixed> $mapping
     */
    private function getIdentifierTypeFromMappings(array $mapping): string
    {
        $valueTypes = array_map(static function ($value): string {
            return gettype($value);
        }, $mapping);
        $uniqueValueTypes = array_unique($valueTypes);
        if (count($uniqueValueTypes) === 1) {
            $identifierType = reset($uniqueValueTypes);
            if ($identifierType === 'integer') {
                $identifierType = 'int';
            }
        } else {
            $identifierType = 'string';
        }

        return $identifierType;
    }
}
