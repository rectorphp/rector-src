<?php

declare(strict_types=1);

namespace Rector\Console\Style;

use Entropy\Console\Output\OutputColorizer;
use Entropy\Console\Output\OutputPrinter;
use Entropy\Console\Output\ProgressBar;

final readonly class RectorStyleFactory
{
    /**
     * @api
     */
    public function create(): RectorStyle
    {
        $outputColorizer = new OutputColorizer();
        $outputPrinter = new OutputPrinter($outputColorizer);
        $progressBar = new ProgressBar();

        $rectorStyle = new RectorStyle($outputPrinter, $outputColorizer, $progressBar);

        $argv = $_SERVER['argv'] ?? [];

        // --debug is called
        if (in_array('--debug', $argv, true)) {
            $rectorStyle->setVerbosity(RectorStyle::VERBOSITY_DEBUG);
        }

        // disable output for tests
        if ($this->isPHPUnitRun()) {
            $rectorStyle->setVerbosity(RectorStyle::VERBOSITY_QUIET);
        }

        return $rectorStyle;
    }

    /**
     * Never ever used static methods if not necessary, this is just handy for tests + src to prevent duplication.
     */
    private function isPHPUnitRun(): bool
    {
        return defined('PHPUNIT_COMPOSER_INSTALL') || defined('__PHPUNIT_PHAR__');
    }
}
