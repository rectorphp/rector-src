<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

class NameExtendsNotExists extends \NotExistsClass
{
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

class NameExtendsNotExists extends \NewClass
{
}

?>
