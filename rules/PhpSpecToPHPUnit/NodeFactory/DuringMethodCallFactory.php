<?php

declare(strict_types=1);

namespace Rector\PhpSpecToPHPUnit\NodeFactory;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Identifier;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\PhpParser\Node\Value\ValueResolver;
use Rector\PostRector\Collector\NodesToAddCollector;

final class DuringMethodCallFactory
{
    public function __construct(
        private ValueResolver $valueResolver,
        private NodesToAddCollector $nodesToAddCollector
    ) {
    }

    public function create(MethodCall $methodCall, PropertyFetch $propertyFetch): MethodCall
    {
        if (! isset($methodCall->getArgs()[0])) {
            throw new ShouldNotHappenException();
        }

        $name = $this->valueResolver->getValue($methodCall->getArgs()[0]->value);
        $thisObjectPropertyMethodCall = new MethodCall($propertyFetch, $name);

        if (isset($methodCall->getArgs()[1]) && $methodCall->getArgs()[1]->value instanceof Array_) {
            /** @var Array_ $array */
            $array = $methodCall->getArgs()[1]->value;

            if (isset($array->items[0])) {
                $thisObjectPropertyMethodCall->getArgs()[] = new Arg($array->items[0]->value);
            }
        }

        /** @var MethodCall $parentMethodCall */
        $parentMethodCall = $methodCall->var;
        $parentMethodCall->name = new Identifier('expectException');

        // add $this->object->someCall($withArgs)
        $this->nodesToAddCollector->addNodeAfterNode($thisObjectPropertyMethodCall, $methodCall);

        return $parentMethodCall;
    }
}
