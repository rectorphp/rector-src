<?php

namespace Company\Commands\Queue;

use CompanyB\Queue;

$app = new App();
$queue = $app->run(\Queue::class);

?>
-----
<?php

namespace Company\Commands\Queue;

use Illuminate\Support\Facades\Queue;

$app = new App();
$queue = $app->run(Queue::class);

?>
