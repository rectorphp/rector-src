<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

final class SkipMethodReferenceInAnonymousClass
{
    public function run()
    {
        return new class {
            public function run()
            {
                return [
                    'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\SkipMethodReferenceInAnonymousClass::indexAction',
                    'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\SkipMethodReferenceInAnonymousClass::' . $this->name(),
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
