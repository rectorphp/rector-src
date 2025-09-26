<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\Issue9388\Source\AnnotationToAttribute;

use PhpParser\Node\Arg;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Attribute;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\PhpParser\Node\Value\ValueResolver;

final readonly class ValidateAttributeDecorator implements AttributeDecoratorInterface
{
    public function __construct(private ValueResolver $valueResolver, private StringClassNameToClassConstantRector $stringClassNameToClassConstantRector)
    {
    }

    public function supports(string $phpAttributeName): bool
    {
        return $phpAttributeName === 'TYPO3\CMS\Extbase\Annotation\Validate';
    }

    public function decorate(Attribute $attribute): void
    {
        $array = new Array_();

        foreach ($attribute->args as $arg) {
            $key = $arg->name instanceof Identifier ? new String_($arg->name->toString()) : new String_('validator');

            if ($this->valueResolver->isValue($key, 'validator')) {
                $classNameString = $this->valueResolver->getValue($arg->value);
                if (! is_string($classNameString)) {
                    continue;
                }

                $className = ltrim($classNameString, '\\');
                $classConstant = $this->stringClassNameToClassConstantRector->refactor(new String_($className));
                $value = $classConstant instanceof ClassConstFetch ? $classConstant : $arg->value;
            } else {
                $value = $arg->value;
            }

            $array->items[] = new ArrayItem($value, $key);
        }

        $attribute->args = [new Arg($array)];
    }
}
