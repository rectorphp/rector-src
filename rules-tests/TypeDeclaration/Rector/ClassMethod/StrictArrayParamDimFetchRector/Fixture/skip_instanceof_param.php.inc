<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Source\Lead;

class SkipInstanceofParam
{
    public function populateCompanyData($entity)
    {
        if ($entity instanceof Lead) {
            $fields = $entity->getPrimaryCompany();
        } else {
            $fields = $entity['primaryCompany'];
        }

        return $fields;
    }
}
