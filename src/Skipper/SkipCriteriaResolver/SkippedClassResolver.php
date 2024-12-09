<?php

declare(strict_types=1);

namespace Rector\Skipper\SkipCriteriaResolver;

use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Skipper\Matcher\FileInfoMatcher;
use Rector\Skipper\Skipper\CustomSkipper;
use Rector\Skipper\Skipper\CustomSkipperSerializeWrapper;
use Rector\Skipper\Skipper\FileNodeSkipperInterface;
use Rector\Skipper\Skipper\FilePatternsSkipper;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;

final class SkippedClassResolver
{
    /**
     * @var null|array<string, FileNodeSkipperInterface[]|null>
     */
    private null|array $skippedClasses = null;

    public function __construct(
        private readonly FileInfoMatcher $fileInfoMatcher,
    ) {
    }

    /**
     * @return array<string, FileNodeSkipperInterface[]|null>
     */
    public function resolve(): array
    {
        // disable cache in tests
        if (StaticPHPUnitEnvironment::isPHPUnitRun()) {
            $this->skippedClasses = null;
        }

        // already cached, even only empty array
        if ($this->skippedClasses !== null) {
            return $this->skippedClasses;
        }

        $skip = SimpleParameterProvider::provideArrayParameter(Option::SKIP);
        $this->skippedClasses = [];

        foreach ($skip as $key => $value) {
            // e.g. [SomeClass::class] → shift values to [SomeClass::class => null]
            if (is_int($key)) {
                $key = $value;
                $value = null;
            }

            if (! is_string($key)) {
                continue;
            }

            // this only checks for Rector rules, that are always autoloaded
            if (! class_exists($key) && ! interface_exists($key)) {
                continue;
            }

            if (is_array($value)) {
                $this->skippedClasses[$key] = [];
                $strings = [];
                foreach ($value as $val) {
                    if (is_string($val)) {
                        $strings[] = $val;
                    } elseif ($val instanceof CustomSkipperSerializeWrapper) {
                        $this->skippedClasses[$key][] = new CustomSkipper($val->customSkipper);
                    }
                }

                if ($strings !== []) {
                    $this->skippedClasses[$key][] = new FilePatternsSkipper($this->fileInfoMatcher, $strings);
                }
            } else {
                $this->skippedClasses[$key] = null;
            }
        }

        return $this->skippedClasses;
    }
}
