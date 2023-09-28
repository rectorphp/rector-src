<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

abstract class SkipVariableVariable5
{
    public function run()
    {
        $$get_data = $get_data->Where(function ($query) use ($field, $key) {
           $query->where($key, '!=', $field['search_value'][0]['val'])->orWhere($key, 'exists', false);
        });
    }
}
