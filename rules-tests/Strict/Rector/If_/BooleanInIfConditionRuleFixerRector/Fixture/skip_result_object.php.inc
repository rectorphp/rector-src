<?php

class ForwardCompatibilityResult {

}

class QueryBuilder {
    /**
     * Executes this query using the bound parameters and their types.
     *
     * @return ForwardCompatibilityResult|int
     *
     * @throws Exception
     */
    public function execute()
    {
        if ($this->type === self::SELECT) {
            return new ForwardCompatibilityResult();
        }

        return 1;
    }
}

class SkipResultObject
{
    public function insert(): bool {
        $queryBuilder = new QueryBuilder();
        if ($queryBuilder->execute()) {
            return false;
        }

        throw new \RuntimeException('...');
    }
}
