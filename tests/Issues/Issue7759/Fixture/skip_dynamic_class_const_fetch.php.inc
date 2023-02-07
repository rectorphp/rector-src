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
-----
<?php

namespace Rector\Core\Tests\Issues\Issue7759\Fixture;

use App\Repository\TestRepository;
use Doctrine\ORM\QueryBuilder;

final class SkipContradictions
{
    /**
     * @return array{query_builder: Closure(\App\Repository\TestRepository $testRepository):\Doctrine\ORM\QueryBuilder}
     */
    public static function testMethod(): array
    {
        return [
            'query_builder' => static fn(TestRepository $testRepository): QueryBuilder => $testRepository->createQb(),
        ];
    }
}
