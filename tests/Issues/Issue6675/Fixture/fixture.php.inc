<?php

namespace Rector\Core\Tests\Issues\Issue6675\Fixture;

$db = mysql_connect("server","user","password");
echo mysql_error();

?>
-----
<?php

namespace Rector\Core\Tests\Issues\Issue6675\Fixture;

$db = mysqli_connect("server","user","password");
<<<<<<< HEAD
echo mysqli_error($db);
=======
<<<<<<< HEAD
echo mysql_error();
=======
echo mysql_error($db);
>>>>>>> 1f3994e5b... skip rename for now, till re-traversing
>>>>>>> restore state before PHPStan 1.0

?>
