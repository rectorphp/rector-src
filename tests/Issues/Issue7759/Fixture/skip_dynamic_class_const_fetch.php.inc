<?php

namespace Rector\Core\Tests\Issues\Issue7759\Fixture;

use App\Repository\TestRepository;
use Doctrine\ORM\QueryBuilder;

final class SkipContradictions
{
    /**
     * @return mixed
     */
    public static function testMethod(): array
    {
        return [
            'query_builder' => static fn(TestRepository $testRepository): QueryBuilder => $testRepository->createQb(),
        ];
    }
}
