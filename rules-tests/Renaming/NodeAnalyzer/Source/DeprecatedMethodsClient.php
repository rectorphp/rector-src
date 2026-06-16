<?php

declare(strict_types=1);

namespace Rector\Tests\Renaming\NodeAnalyzer\Source;

final class DeprecatedMethodsClient
{
    /**
     * @deprecated since 2.0, use fetchData() instead
     */
    public function getData(): array
    {
        return $this->fetchData();
    }

    /**
     * @deprecated replaced by fetchData()
     */
    public function loadData(): array
    {
        return $this->fetchData();
    }

    /**
     * @deprecated {@see fetchData()}
     */
    public function readData(): array
    {
        return $this->fetchData();
    }

    /**
     * @deprecated since 2.0, use the repository layer instead
     */
    public function legacyData(): array
    {
        return $this->fetchData();
    }

    /**
     * @deprecated use missingMethod() instead
     */
    public function vanishedData(): array
    {
        return $this->fetchData();
    }

    /**
     * @deprecated use loadData() instead
     */
    public function deadEndData(): array
    {
        return $this->fetchData();
    }

    public function fetchData(): array
    {
        return [];
    }

    /**
     * @deprecated use make() instead
     */
    public static function makeOld(): self
    {
        return new self();
    }

    public static function make(): self
    {
        return new self();
    }
}
