<?php

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer")
 * )
 */
class SchemaWithPositionalParameterDto
{
}
-----
<?php

namespace Utils\Rector\Tests\Rector\WrapAnnotationParametersRector\Fixture;

/**
 * @OA\Schema
 */
class SchemaWithPositionalParameterDto
{
}
