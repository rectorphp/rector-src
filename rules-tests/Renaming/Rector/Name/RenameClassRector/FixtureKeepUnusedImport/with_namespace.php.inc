<?php

namespace App;

use Psr\SimpleCache\CacheInterface;

class NoNamespace implements CacheInterface
{
}

?>
-----
<?php

namespace App;

use Psr\SimpleCache\CacheInterface;

class NoNamespace implements \Symfony\Contracts\Cache\CacheInterface
{
}

?>
