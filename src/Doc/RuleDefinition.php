<?php

declare(strict_types=1);

namespace Rector\Doc;

use Rector\Doc\CodeSample\ConfiguredCodeSample;
use Rector\RuleDoc\Contract\CodeSampleInterface;
use Rector\RuleDoc\Exception\PoorDocumentationException;
use Rector\RuleDoc\Exception\ShouldNotHappenException;

/**
 * @api
 */
final class RuleDefinition
{
    private ?string $ruleClass = null;

    private ?string $ruleFilePath = null;

    /**
     * @var CodeSampleInterface[]
     */
    private readonly array $codeSamples;

    /**
     * @param CodeSampleInterface[] $codeSamples
     */
    public function __construct(
        private readonly string $description,
        array $codeSamples
    ) {
        if ($codeSamples === []) {
            throw new PoorDocumentationException(
                'Provide at least one code sample, so people can practically see what the rule does'
            );
        }

        $this->codeSamples = $codeSamples;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setRuleClass(string $ruleClass): void
    {
        $this->ruleClass = $ruleClass;
    }

    public function getRuleClass(): string
    {
        if ($this->ruleClass === null) {
            throw new ShouldNotHappenException();
        }

        return $this->ruleClass;
    }

    public function setRuleFilePath(string $ruleFilePath): void
    {
        // fir relative file path for GitHub
        $this->ruleFilePath = ltrim($ruleFilePath, '/');
    }

    public function getRuleFilePath(): string
    {
        if ($this->ruleFilePath === null) {
            throw new ShouldNotHappenException();
        }

        return $this->ruleFilePath;
    }

    public function getRuleShortClass(): string
    {
        if ($this->ruleClass === null) {
            throw new ShouldNotHappenException();
        }

        // get short class name
        return basename(str_replace('\\', '/', $this->ruleClass));
    }

    /**
     * @return CodeSampleInterface[]
     */
    public function getCodeSamples(): array
    {
        return $this->codeSamples;
    }

    public function isConfigurable(): bool
    {
        return array_any($this->codeSamples, fn (CodeSampleInterface $codeSample): bool => $codeSample instanceof ConfiguredCodeSample);
    }
}
