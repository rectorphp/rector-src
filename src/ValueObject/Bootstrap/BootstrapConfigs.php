<?php

declare(strict_types=1);

namespace Rector\ValueObject\Bootstrap;

final readonly class BootstrapConfigs
{
    /**
     * @param string[] $setConfigFiles
     */
    public function __construct(
        private ?string $mainConfigFile,
        private array $setConfigFiles
    ) {
    }

    public function getMainConfigFile(): ?string
    {
        return $this->mainConfigFile;
    }

    /**
     * @return string[]
     */
    public function getConfigFiles(): array
    {
        $configFiles = [];
        if ($this->mainConfigFile !== null) {
            $configFiles[] = $this->mainConfigFile;
        }

        return array_merge($configFiles, $this->setConfigFiles);
    }
}
