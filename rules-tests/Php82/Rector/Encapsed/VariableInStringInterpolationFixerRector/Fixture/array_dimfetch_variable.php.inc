<?php

declare(strict_types=1);

namespace Rector\Tests\Php82\Rector\Encapsed\VariableInStringInterpolationFixerRector\Fixture;

$array = [
  'game' => 'basketball',
];
echo "I like playing ${array['game']}";

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php82\Rector\Encapsed\VariableInStringInterpolationFixerRector\Fixture;

$array = [
  'game' => 'basketball',
];
echo "I like playing {$array['game']}";

?>
