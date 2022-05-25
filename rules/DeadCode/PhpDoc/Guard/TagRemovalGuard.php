<?php

declare(strict_types=1);

namespace Rector\DeadCode\PhpDoc\Guard;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocChildNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTextNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\BetterPhpDocParser\ValueObject\PhpDoc\VariadicAwareParamTagValueNode;
use Rector\BetterPhpDocParser\ValueObject\PhpDocAttributeKey;
use Rector\BetterPhpDocParser\ValueObject\Type\BracketsAwareUnionTypeNode;
use Rector\DeadCode\TypeNodeAnalyzer\GenericTypeNodeAnalyzer;
use Rector\DeadCode\TypeNodeAnalyzer\MixedArrayTypeNodeAnalyzer;

final class TagRemovalGuard
{
    public function __construct(
        private readonly MixedArrayTypeNodeAnalyzer $mixedArrayTypeNodeAnalyzer,
        private readonly GenericTypeNodeAnalyzer $genericTypeNodeAnalyzer
    ) {
    }

    public function isLegal(ParamTagValueNode|ReturnTagValueNode|VarTagValueNode $tagValueNode, Node $node): bool
    {
        if (in_array($tagValueNode->type::class, PhpDocTypeChanger::ALLOWED_TYPES, true)) {
            return false;
        }

        if (! $tagValueNode->type instanceof BracketsAwareUnionTypeNode) {
            return $this->isEmptyDescription($tagValueNode, $node);
        }

        if ($this->mixedArrayTypeNodeAnalyzer->hasMixedArrayType($tagValueNode->type)) {
            return false;
        }

        if ($this->hasTruePseudoType($tagValueNode->type)) {
            return false;
        }

        if ($this->genericTypeNodeAnalyzer->hasGenericType($tagValueNode->type)) {
            return false;
        }

        return $this->isEmptyDescription($tagValueNode, $node);
    }

    private function hasTruePseudoType(BracketsAwareUnionTypeNode $bracketsAwareUnionTypeNode): bool
    {
        $unionTypes = $bracketsAwareUnionTypeNode->types;

        foreach ($unionTypes as $unionType) {
            if (! $unionType instanceof IdentifierTypeNode) {
                continue;
            }

            $name = strtolower((string) $unionType);
            if ($name === 'true') {
                return true;
            }
        }

        return false;
    }

    private function isEmptyDescription(ParamTagValueNode|ReturnTagValueNode $tagValueNode, Node $node): bool
    {
        if ($tagValueNode->description !== '') {
            return false;
        }

        $parent = $tagValueNode->getAttribute(PhpDocAttributeKey::PARENT);
        if (! $parent instanceof PhpDocTagNode) {
            return true;
        }

        $parent = $parent->getAttribute(PhpDocAttributeKey::PARENT);
        if (! $parent instanceof PhpDocNode) {
            return true;
        }

        $children = $parent->children;

        foreach ($children as $key => $child) {
            if ($child instanceof PhpDocTagNode && $node instanceof FullyQualified) {
                return $this->isUnionIdentifier($child);
            }

            if (! $this->isTextNextline($key, $child)) {
                return false;
            }
        }

        return true;
    }

    private function isUnionIdentifier(PhpDocTagNode $phpDocTagNode): bool
    {
        if (! $phpDocTagNode->value instanceof VariadicAwareParamTagValueNode) {
            return true;
        }

        if (! $phpDocTagNode->value->type instanceof BracketsAwareUnionTypeNode) {
            return true;
        }

        $types = $phpDocTagNode->value->type->types;
        foreach ($types as $type) {
            if ($type instanceof IdentifierTypeNode) {
                return false;
            }
        }

        return true;
    }

    private function isTextNextline(int $key, PhpDocChildNode $phpDocChildNode): bool
    {
        if ($key < 1) {
            return true;
        }

        if (! $phpDocChildNode instanceof PhpDocTextNode) {
            return true;
        }

        return (string) $phpDocChildNode === '';
    }
}
