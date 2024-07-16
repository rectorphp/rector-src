<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector\Fixture;

final class UnionOnMultipleCallsNullable
{
    public function hashSet(string $key, ?array $value): void {
    }

    public function valueSet(string $key, string $value): void {
    }

    public function hashSet2(string $key, array $value): void {
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

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector\Fixture;

final class UnionOnMultipleCallsNullable
{
    public function hashSet(string $key, ?array $value): void {
    }

    public function valueSet(string $key, string $value): void {
    }

    public function hashSet2(string $key, array $value): void {
    }

    public function set(string $type, string $key, null|array|string $value): void {
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

?>
