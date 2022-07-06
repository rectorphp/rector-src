<?php
declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Source;

class Resource
{
    /**
	 * @param literal-string $input
     */
    public function _($input): string
    {
    	return (string)$input; // dummy oode
    }
}