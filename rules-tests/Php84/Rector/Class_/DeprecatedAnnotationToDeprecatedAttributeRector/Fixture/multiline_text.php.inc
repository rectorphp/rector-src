<?php

namespace Rector\Tests\Php84\Rector\Class_\DeprecatedAnnotationToDeprecatedAttributeRector;

final class MultilineText
{
    /**
     * @deprecated a reason of the deprecated
     * written in two lines
     */
    public function auth()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\Class_\DeprecatedAnnotationToDeprecatedAttributeRector;

final class MultilineText
{
    #[\Deprecated(message: <<<'TXT'
    a reason of the deprecated
     written in two lines
    TXT)]
    public function auth()
    {
    }
}

?>
