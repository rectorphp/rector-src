<?php

namespace Rector\Tests\Php84\Rector\Class_\DeprecatedAnnotationToDeprecatedAttributeRector;

final class SingleLineText
{
    /**
     * @deprecated a reason of the deprecated
     */
    public function auth()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\Class_\DeprecatedAnnotationToDeprecatedAttributeRector;

final class SingleLineText
{
    #[\Deprecated(message: 'a reason of the deprecated')]
    public function auth()
    {
    }
}

?>
