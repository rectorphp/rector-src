<?php

namespace Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector\Fixture;

function skip_mixed_passed_to_round(array $data): float
{
    return round($data['value'], 2);
}
