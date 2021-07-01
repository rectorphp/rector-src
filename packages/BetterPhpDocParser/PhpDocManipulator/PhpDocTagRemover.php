<?php

declare(strict_types=1);

namespace Rector\BetterPhpDocParser\PhpDocManipulator;

use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use Rector\BetterPhpDocParser\PhpDoc\DoctrineAnnotationTagValueNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Symplify\SimplePhpDocParser\PhpDocNodeTraverser;

final class PhpDocTagRemover
{
    public function removeByName(PhpDocInfo $phpDocInfo, string $name): void
    {
        $phpDocNode = $phpDocInfo->getPhpDocNode();

        foreach ($phpDocNode->children as $key => $phpDocChildNode) {
            if (! $phpDocChildNode instanceof PhpDocTagNode) {
                continue;
            }

            if ($this->areAnnotationNamesEqual($name, $phpDocChildNode->name)) {
                unset($phpDocNode->children[$key]);
                $phpDocInfo->markAsChanged();
            }

            if ($phpDocChildNode->value instanceof DoctrineAnnotationTagValueNode) {
                $doctrineAnnotationTagValueNode = $phpDocChildNode->value;

                if ($doctrineAnnotationTagValueNode->hasClassName($name)) {
                    unset($phpDocNode->children[$key]);
                    $phpDocInfo->markAsChanged();
                }
            }
        }
    }

    public function removeTagValueFromNode(PhpDocInfo $phpDocInfo, Node $desiredNode): void
    {
        $phpDocNode = $phpDocInfo->getPhpDocNode();

        $phpDocNodeTraverser = new PhpDocNodeTraverser();
        $phpDocNodeTraverser->traverseWithCallable($phpDocNode, '', function ($node) use ($desiredNode, $phpDocInfo) {
            if ($node !== $desiredNode) {
                return $node;
            }

            $phpDocInfo->markAsChanged();

            return PhpDocNodeTraverser::NODE_REMOVE;
        });
    }

    private function areAnnotationNamesEqual(string $firstAnnotationName, string $secondAnnotationName): bool
    {
        $firstAnnotationName = trim($firstAnnotationName, '@');
        $secondAnnotationName = trim($secondAnnotationName, '@');

        return $firstAnnotationName === $secondAnnotationName;
    }
}
