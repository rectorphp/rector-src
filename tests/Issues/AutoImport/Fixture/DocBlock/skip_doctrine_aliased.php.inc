<?php

namespace App;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Text comment
 *
 * @MongoDB\Document(
 *     repositoryClass="App\Repository\SomeRepository",
 *     collection="SomeLog"
 * )
 */
class SkipDoctrineAliased
{
}
