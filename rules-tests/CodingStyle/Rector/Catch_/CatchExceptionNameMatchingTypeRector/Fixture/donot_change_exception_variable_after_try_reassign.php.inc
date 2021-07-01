<?php

namespace Rector\Tests\CodingStyle\Rector\Catch_\CatchExceptionNameMatchingTypeRector\Fixture;

class DonotChangeExceptionVariableAfterTryReassign
{
    public function run()
    {
        try {
        } catch (SomeException $typoException) {
        }

        $typoException = 'test';
        $this->verify($typoException);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Catch_\CatchExceptionNameMatchingTypeRector\Fixture;

class DonotChangeExceptionVariableAfterTryReassign
{
    public function run()
    {
        try {
        } catch (SomeException $someException) {
        }

        $typoException = 'test';
        $this->verify($typoException);
    }
}

?>
