<?php

namespace Rector\Tests\CodingStyle\Rector\String_\UseClassKeywordForClassNameResolutionRector\Fixture;

class MultipleOccurrencesInOneString
{
    public function run()
    {
        return 'Rector\Tests\CodingStyle\Rector\String_\UseClassKeywordForClassNameResolutionRector\Fixture\MultipleOccurrencesInOneString::staticCall() && Rector\CodingStyle\Rector\String_\UseClassKeywordForClassNameResolutionRector\Fixture\MultipleOccurrencesInOneString::staticCall()';
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\String_\UseClassKeywordForClassNameResolutionRector\Fixture;

class MultipleOccurrencesInOneString
{
    public function run()
    {
        return \Rector\Tests\CodingStyle\Rector\String_\UseClassKeywordForClassNameResolutionRector\Fixture\MultipleOccurrencesInOneString::class . '::staticCall() && Rector\CodingStyle\Rector\String_\UseClassKeywordForClassNameResolutionRector\Fixture\MultipleOccurrencesInOneString::staticCall()';
    }
}

?>
