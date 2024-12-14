<?php

declare(strict_types=1);

namespace Rector\Php80\Rector\Class_;

use Nette\Utils\Strings;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use Rector\BetterPhpDocParser\PhpDoc\DoctrineAnnotationTagValueNode;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Php80\ValueObject\AnnotationToAttribute;
use Rector\Php80\ValueObject\AttributeValueAndDocComment;
use Rector\Util\NewLineSplitter;

final class AttributeValueResolver
{
    /**
     * @var string
     * @see https://regex101.com/r/CL9ktz/4
     */
    private const END_SLASH_REGEX = '#\\\\$#';

    public function resolve(
        AnnotationToAttribute $annotationToAttribute,
        PhpDocTagNode $phpDocTagNode
    ): ?AttributeValueAndDocComment {
        if (! $annotationToAttribute->getUseValueAsAttributeArgument()) {
            return null;
        }

        $docCommentAppend = '';

        $docValue = (string) $phpDocTagNode->value;

        /*if ($phpDocTagNode->value instanceof DoctrineAnnotationTagValueNode) {
            $docValue = (string) $phpDocTagNode->value->getOriginalContent();
            $attributeComment = (string) $phpDocTagNode->value->getAttribute(AttributeKey::ATTRIBUTE_COMMENT);
            $strippedDocValue = rtrim($docValue, $attributeComment);

            dump($docValue);
            //dump($attributeComment);
            //die;

            if (str_starts_with($docValue, '(') && $attributeComment !== '') {
                $docValue = $strippedDocValue;
                $docCommentAppend = $attributeComment;die('here');
            }
        } else {
            $docValue = (string) $phpDocTagNode->value;
        }*/

        $docComment = '';

        // special case for newline
        if (str_contains($docValue, "\n")) {
            $keepJoining = true;
            $docValueLines = NewLineSplitter::split($docValue);

            $joinDocValue = '';

            $hasPreviousEndSlash = false;

            foreach ($docValueLines as $key => $docValueLine) {
                if ($keepJoining) {
                    $joinDocValue .= rtrim($docValueLine, '\\\\');
                }

                if (Strings::match($docValueLine, self::END_SLASH_REGEX) === null) {
                    if ($hasPreviousEndSlash === false && $key > 0) {
                        if ($docComment === '') {
                            $docComment .= $docValueLine;
                        } else {
                            $docComment .= "\n * " . $docValueLine;
                        }
                    }

                    $keepJoining = false;
                } else {
                    $hasPreviousEndSlash = true;
                }
            }

            $docValue = $joinDocValue;
        }

        return new AttributeValueAndDocComment($docValue, $docComment);
    }
}
