<?php

declare(strict_types=1);

namespace Rector\Composer\Contract\Rector;

use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Contract\Rector\RectorInterface;
use Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;

interface ComposerRectorInterface extends ConfigurableRectorInterface, RectorInterface
{
    public function refactor(ComposerJson $composerJson): void;
}
