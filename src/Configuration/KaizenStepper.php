<?php

declare(strict_types=1);

namespace Rector\Configuration;

use Rector\Caching\Detector\KaizenRulesDetector;
use Rector\Contract\Rector\RectorInterface;

final class KaizenStepper
{
    /**
     * @var positive-int|null
     */
    private ?int $stepCount = null;

    public function __construct(
        private readonly KaizenRulesDetector $kaizenRulesDetector
    ) {
    }

    public function start(): void
    {
        $this->kaizenRulesDetector->clean();
    }

    /**
     * @param positive-int $stepCount
     */
    public function setStepCount(int $stepCount): void
    {
        $this->stepCount = $stepCount;
    }

    public function enabled(): bool
    {
        return $this->stepCount !== null;
    }

    /**
     * @param class-string<RectorInterface> $rectorClass
     */
    public function recordAppliedRule(string $rectorClass): void
    {
        $this->kaizenRulesDetector->addRule($rectorClass);
    }

    public function shouldKeepImproving(string $rectorClass): bool
    {
        $appliedRectorClasses = $this->kaizenRulesDetector->loadRules();

        // is rule already in applied rules? keep going
        if (in_array($rectorClass, $appliedRectorClasses)) {
            return true;
        }

        // make sure we made enough changes
        return count($appliedRectorClasses) < $this->stepCount;
    }
}
