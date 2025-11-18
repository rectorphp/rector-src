<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';


// 1. copy file from vendor
// load file contents from venodr

// modify clas name + namespace

// add it here as ImmutableNodeTraverser.php

\Nette\Utils\FileSystem::copy(__DIR__ . '/../vendor/nikic/php-parser/lib/PhpParser/NodeTraverser.php', __DIR__ . '/../src/PhpParser/NodeTraverser/ImmutableNodeTraverser.php');
