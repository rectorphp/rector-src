<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

final class MethodReferenceInAnonymousClass
{
    public function run()
    {
        return new class {
            public function run()
            {
                return [
                    'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\MethodReferenceInAnonymousClass::indexAction',
                    'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\MethodReferenceInAnonymousClass::' . $this->name(),
                ];
            }

            private function name(): string
            {
                return 'indexAction';
            }
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

final class MethodReferenceInAnonymousClass
{
    public function run()
    {
        return new class {
            public function run()
            {
                return [
                    \Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\MethodReferenceInAnonymousClass::class . '::indexAction',
                    \Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\MethodReferenceInAnonymousClass::class . '::' . $this->name(),
                ];
            }

            private function name(): string
            {
                return 'indexAction';
            }
        };
    }
}

?>
