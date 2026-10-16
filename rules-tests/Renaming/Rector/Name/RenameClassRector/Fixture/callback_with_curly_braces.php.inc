<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

use Symfony\Component\Validator\Constraints as Assert;

final class CallbackWithCurlyBraces
{
    /**
     * @Assert\Choice(callback={"MyNamespace\MylegacyClass", "getChoices"})
     */
    private $attribute;
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

use Symfony\Component\Validator\Constraints as Assert;

final class CallbackWithCurlyBraces
{
    /**
     * @Assert\Choice(callback={"MyNewNamespace\MyNewClass", "getChoices"})
     */
    private $attribute;
}

?>
