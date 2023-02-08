<?php

namespace Company\Commands\Queue;

use CompanyB\Queue;

$queue = App::run(\Queue::class);

?>
-----
<?php

namespace Company\Commands\Queue;

use Illuminate\Support\Facades\Queue;

$queue = App::run(Queue::class);

?>
