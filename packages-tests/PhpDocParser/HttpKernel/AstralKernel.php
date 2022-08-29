<?php

declare(strict_types=1);

namespace Rector\Tests\PhpDocParser\HttpKernel;

use Psr\Container\ContainerInterface;
use Rector\PhpDocParser\ValueObject\AstralConfig;
use Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel;

final class AstralKernel extends AbstractSymplifyKernel
{
    /**
     * @param string[] $configFiles
     */
    public function createFromConfigs(array $configFiles): ContainerInterface
    {
        $configFiles[] = AstralConfig::FILE_PATH;
        return $this->create($configFiles);
    }
}
