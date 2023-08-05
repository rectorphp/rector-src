<?php

namespace Rector\Tests\TypeDeclaration\Rector\Ternary\FlipNegatedTernaryInstanceofRector\Fixture;

echo ! $object instanceof Product ? null : $object->getPrice();

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Ternary\FlipNegatedTernaryInstanceofRector\Fixture;

echo $object instanceof Product ? $object->getPrice() : null;

?>
