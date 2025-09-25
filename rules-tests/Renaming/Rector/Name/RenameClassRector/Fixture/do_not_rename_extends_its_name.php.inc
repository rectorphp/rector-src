<?php

namespace MyNamespace;

abstract class AbstractMigration {}

abstract class BaseMigration extends AbstractMigration {}

class MyMigration extends AbstractMigration {}

?>
-----
<?php

namespace MyNamespace;

abstract class AbstractMigration {}

abstract class BaseMigration extends AbstractMigration {}

class MyMigration extends \MyNamespace\BaseMigration {}

?>
