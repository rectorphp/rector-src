<?php

declare(strict_types=1);

namespace Rector\Doc\CodeSample;

use Rector\Doc\AbstractCodeSample;
use Rector\RuleDoc\Contract\CodeSampleInterface;
use Rector\RuleDoc\Exception\ShouldNotHappenException;

/**
 * @api
 */
final class ConfiguredCodeSample extends AbstractCodeSample implements CodeSampleInterface
{
    /**
     * @var mixed[]
     */
    private readonly array $configuration;

    /**
     * @param mixed[] $configuration
     */
    public function __construct(string $badCode, string $goodCode, array $configuration)
    {
        if ($configuration === []) {
            $message = sprintf('Configuration cannot be empty. Look for "%s"', $badCode);
            throw new ShouldNotHappenException($message);
        }

        $this->configuration = $configuration;

        parent::__construct($badCode, $goodCode);
    }

    /**
     * @return mixed[]
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }
}
