<?php

declare(strict_types=1);

namespace PhpMyAdmin\Bookmarks;

use PhpMyAdmin\ConfigStorage\Features\BookmarkFeature;
use PhpMyAdmin\ConfigStorage\Relation;
use PhpMyAdmin\DatabaseInterface;

class SkipAssignWithMethodCallPropertySameName
{
    private BookmarkFeature|null $bookmarkFeature;

    public function __construct(private DatabaseInterface $dbi, Relation $relation)
    {
        $this->bookmarkFeature = $relation->getRelationParameters()->bookmarkFeature;
    }
}