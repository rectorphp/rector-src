<?php

use Psr\SimpleCache\CacheInterface;

class NoNamespace implements CacheInterface
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
