<?php

namespace Rector\Tests\Issues\AutoImportAliasedNamespace\Fixture;

use Rector\Tests\Issues\AutoImportAliasedNamespace\Source\Field;

final class FullyQualifiedStillImported
{
    /**
     * @var \Rector\Tests\Issues\AutoImportAliasedNamespace\Source\Constraints\Choice
     */
    public $choice;

    public function run(): Field\TextField
    {
        new \Rector\Tests\Issues\AutoImportAliasedNamespace\Source\Constraints\NotBlank();

        return new Field\TextField();
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImportAliasedNamespace\Fixture;

use Rector\Tests\Issues\AutoImportAliasedNamespace\Source\Constraints\NotBlank;
use Rector\Tests\Issues\AutoImportAliasedNamespace\Source\Constraints\Choice;
use Rector\Tests\Issues\AutoImportAliasedNamespace\Source\Field;

final class FullyQualifiedStillImported
{
    /**
     * @var Choice
     */
    public $choice;

    public function run(): Field\TextField
    {
        new NotBlank();

        return new Field\TextField();
    }
}

?>
