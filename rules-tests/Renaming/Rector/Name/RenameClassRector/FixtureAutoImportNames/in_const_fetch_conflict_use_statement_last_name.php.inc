<?php

namespace Company\Commands\Queue;

use CompanyB\Queue;

$queue = \Queue::FOO;

?>
-----
<?php

namespace Company\Commands\Queue;

use Illuminate\Support\Facades\Queue;

$queue = Queue::FOO;

?>
