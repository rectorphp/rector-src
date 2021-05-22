<?php

declare(strict_types=1);

namespace Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\FixtureModif;

?>
<h1><?php echo $hello; ?></h1>
-----
<?php
/**
 * @var string $hello
 */
?>
<h1><?php echo $hello; ?></h1>

