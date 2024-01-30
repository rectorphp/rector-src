<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

final class SkipIfTypes
{
    /** @param array{date: array<string, string>} $filters */
    public function addFilters(array &$params, array $filters): array
    {
        if ([] !== $filters['date'] && !empty($filters['date']['start'])) {
            $this->addFilterDate($params, $filters['date']);
        }
        return $params;
    }

    /** @param array{type: null|string, start: null|string, end: null|string, dateLimit: null|string} $date */
    private function addFilterDate(array &$params, array $date): array { }
}
