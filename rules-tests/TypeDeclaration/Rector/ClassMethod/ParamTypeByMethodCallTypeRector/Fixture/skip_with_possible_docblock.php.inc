<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector\Fixture;

final class SkipWithPossibleDocblock
{
    public function hashSet(string $key, array $value): void {
    }

    /**
     * @param string $value
     */
    public function valueSet(string $key, $value): void {
    }

    public function set(string $type, string $key, $value): void {
    	switch ($type) {
            case 'hash':
                return $this->hashSet($key, $value);
            case 'value':
                return $this->valueSet($key, $value);
            case 'hash2':
                return $this->hashSet2($key, $value);
            default:
                throw new \Exception('ohno');
        }
    }
}
