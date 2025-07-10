<?php

declare(strict_types=1);

namespace Rector\Tests\Php82\Rector\Encapsed\VariableInStringInterpolationFixerRector\Fixture;

$array = [
  'game' => 'basketball',
];
$c = "football";
echo "I like playing ${c}";
echo "I also like playing ${array['game']}";

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php82\Rector\Encapsed\VariableInStringInterpolationFixerRector\Fixture;

$array = [
  'game' => 'basketball',
];
$c = "football";
echo "I like playing {$c}";
echo "I also like playing {$array['game']}";
?>
