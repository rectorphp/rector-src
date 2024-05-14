<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\MixedTypeRector\Fixture;

use Rector\Tests\Php80\Rector\FunctionLike\MixedTypeRector\Source\ConcreteFileFilter;
use Rector\Tests\Php80\Rector\FunctionLike\MixedTypeRector\Source\FileFilter;

/**
 * @param ConcreteFileFilter|FileFilter $fileFilter
 */
function usesFileFilter(FileFilter $fileFilter) {}
