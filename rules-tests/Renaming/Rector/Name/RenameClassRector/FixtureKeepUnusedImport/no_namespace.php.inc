<?php

use Psr\SimpleCache\CacheInterface;

class NoNamespace implements \Psr\SimpleCache\CacheInterface
{
}

?>
-----
<?php

use Psr\SimpleCache\CacheInterface;

class NoNamespace implements \Symfony\Contracts\Cache\CacheInterface
{
}

?>
