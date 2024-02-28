<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

final class SkipCallOnClosureNamedArg
{
    public function __invoke(Request $request)
    {
        return Client::query()->businesses()
            ->when($request->isMethod('get'), function (EloquentQueryBuilder $query): void {
                //
            })->paginate(
                perPage: $request->validated('length')
            );
    }
}
