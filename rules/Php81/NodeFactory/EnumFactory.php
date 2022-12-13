<?php

declare(strict_types=1);

namespace Rector\Php81\NodeFactory;

use PhpParser\BuilderFactory;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
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
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly BuilderFactory $builderFactory,
        private readonly ValueResolver $valueResolver
    ) {
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
        $mapping = [];
        foreach ($class->stmts as $stmt) {
            if ($stmt instanceof ClassMethod && 'values' === $stmt->name->name) {
                foreach ($stmt->stmts as $methodStmt) {
                    if (!($methodStmt instanceof Return_)) {
                        continue;
                    }
                    foreach ($methodStmt->expr->items as $item) {
                        $mapping[$item->key->value] = $item->value->value;
                    }
                }
            }
        }


        $docBlockMethods = $phpDocInfo->getTagsByName('@method');
        if ($docBlockMethods !== []) {
            $valueTypes = array_unique(array_map(function ($value) {return gettype($value);}, array_values($mapping)));
            if (count($valueTypes) === 1) {
                $identifierType = reset($valueTypes);
                if ('integer' === $identifierType) {
                    $identifierType = 'int';
                }
            } else {
                $identifierType = 'string';
            }
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

    private function createEnumCaseFromDocComment(PhpDocTagNode $phpDocTagNode, array $mapping): EnumCase
    {
        /** @var MethodTagValueNode $nodeValue */
        $nodeValue = $phpDocTagNode->value;
        if (!empty($mapping[$nodeValue->methodName])) {
            $enumValue = $mapping[$nodeValue->methodName];
        } else {
            $enumValue = $nodeValue->methodName;
        }
        $enumName = strtoupper($nodeValue->methodName);
        $enumExpr = $this->builderFactory->val($enumValue);

        return new EnumCase($enumName, $enumExpr);
    }
}
