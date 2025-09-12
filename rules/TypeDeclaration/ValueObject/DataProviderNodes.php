<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\ValueObject;

use Nette\Utils\Strings;
use PhpParser\Node\Attribute;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use Webmozart\Assert\Assert;

final readonly class DataProviderNodes
{
    /**
     * @see https://regex101.com/r/hW09Vt/1
     * @var string
     */
    private const METHOD_NAME_REGEX = '#^(?<method_name>\w+)(\(\))?#';

    /**
     * @param Attribute[] $attributes
     * @param PhpDocTagNode[] $phpDocTagNodes
     */
    public function __construct(
        private Class_ $class,
        private array $attributes,
        private array $phpDocTagNodes,
    ) {
        Assert::allIsInstanceOf($attributes, Attribute::class);
        Assert::allIsInstanceOf($phpDocTagNodes, PhpDocTagNode::class);
    }

    public function isEmpty(): bool
    {
        return $this->getClassMethods() === [];
    }

    /**
     * @return ClassMethod[]
     */
    public function getClassMethods(): array
    {
        $classMethods = [];

        foreach ($this->phpDocTagNodes as $phpDocTagNode) {
            if ($phpDocTagNode->value instanceof GenericTagValueNode) {
                $methodName = $this->matchMethodName($phpDocTagNode->value->value);
                if (! is_string($methodName)) {
                    continue;
                }

                $classMethod = $this->class->getMethod($methodName);
                if (! $classMethod instanceof ClassMethod) {
                    continue;
                }

                $classMethods[] = $classMethod;
            }
        }

        foreach ($this->attributes as $attribute) {
            $value = $attribute->args[0]->value;
            if (! $value instanceof String_) {
                continue;
            }

            $methodName = $this->matchMethodName($value->value);
            if (! is_string($methodName)) {
                continue;
            }

            $classMethod = $this->class->getMethod($methodName);
            if (! $classMethod instanceof ClassMethod) {
                continue;
            }

            $classMethods[] = $classMethod;
        }

        return $classMethods;
    }

    private function matchMethodName(string $content): ?string
    {
        $match = Strings::match($content, self::METHOD_NAME_REGEX);
        if ($match === null) {
            return null;
        }

        return $match['method_name'];
    }
}
