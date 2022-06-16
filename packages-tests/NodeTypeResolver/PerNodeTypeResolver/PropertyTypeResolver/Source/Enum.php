<?php

declare(strict_types=1);

namespace Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\PropertyTypeResolver\Source;

class Enum
{
    public const MODE_ADD = 'add';
    public const MODE_EDIT = 'edit';
    public const MODE_CLONE = 'clone';

    /**
     * @var self::*
     */
    public $mode;
}
