<?php

declare(strict_types=1);

namespace Rector\VendorLocker;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use Rector\VendorLocker\NodeVendorLocker\ClassMethodParamVendorLockResolver;
use Rector\VendorLocker\NodeVendorLocker\ClassMethodReturnVendorLockResolver;
use Rector\VendorLocker\NodeVendorLocker\PropertyTypeVendorLockResolver;

final class VendorLockResolver
{
    public function __construct(
        private readonly ClassMethodParamVendorLockResolver $classMethodParamVendorLockResolver,
        private readonly ClassMethodReturnVendorLockResolver $classMethodReturnVendorLockResolver,
        private readonly PropertyTypeVendorLockResolver $propertyTypeVendorLockResolver
    ) {
    }

    public function isClassMethodParamLockedIn(Node $node): bool
    {
        if (! $node instanceof ClassMethod) {
            return false;
        }

        return $this->classMethodParamVendorLockResolver->isVendorLocked($node);
    }

    public function isReturnChangeVendorLockedIn(ClassMethod $classMethod): bool
    {
        return $this->classMethodReturnVendorLockResolver->isVendorLocked($classMethod);
    }

    public function isPropertyTypeChangeVendorLockedIn(Property $property): bool
    {
        return $this->propertyTypeVendorLockResolver->isVendorLocked($property);
    }
}
