<?php

declare(strict_types=1);

namespace Doctrine\DBAL;

final class Connection
{
    /**
     * @return list<mixed>
     */
    public function fetchFirstColumn(string $query, array $params = [], array $types = []): array
    {
        return [];
    }
}
