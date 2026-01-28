<?php

namespace Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector\Fixture;

// this included file is not safe
include __DIR__ . '/../Source/NonTypedApp.php';

echo strlen('1');
