<?php

declare(strict_types=1);

namespace Rector\Core\Bootstrap;

use Rector\Core\ValueObject\Bootstrap\BootstrapConfigs;
use Symfony\Component\Console\Input\ArgvInput;
use Symplify\SetConfigResolver\ConfigResolver;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RectorConfigsResolver
{
    private ConfigResolver $configResolver;

    public function __construct()
    {
        $this->configResolver = new ConfigResolver();
    }

    public function provide(): BootstrapConfigs
    {
        $argvInput = new ArgvInput();
        $mainConfigFileInfo = $this->configResolver->resolveFromInputWithFallback($argvInput, ['rector.php']);

        $rectorRecipeConfigFileInfo = $this->resolveRectorRecipeConfig($argvInput);

        $configFileInfos = [];
        if ($rectorRecipeConfigFileInfo !== null) {
            $configFileInfos[] = $rectorRecipeConfigFileInfo;
        }

        return new BootstrapConfigs($mainConfigFileInfo, $configFileInfos);
    }

    private function resolveRectorRecipeConfig(ArgvInput $argvInput): ?SmartFileInfo
    {
        if ($argvInput->getFirstArgument() !== 'generate') {
            return null;
        }

        // autoload rector recipe file if present, just for \Rector\RectorGenerator\Command\GenerateCommand
        $rectorRecipeFilePath = getcwd() . '/rector-recipe.php';
        if (! file_exists($rectorRecipeFilePath)) {
            return null;
        }

        return new SmartFileInfo($rectorRecipeFilePath);
    }
}
