<?php

namespace App;

use Vendor\Config\Repository;
use Vendor\Contracts\Repository as RepositoryContract;

final class AliasedSameShortName
{
    public function run(RepositoryContract $repositoryContract): Repository
    {
        return new Repository();
    }
}
