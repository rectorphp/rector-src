<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

use OpenApi\Annotations as OA;

final class OpenAPIPropertyExampleKeepNumericString
{
    /**
     * @OA\Property(
     *     example="01112"
     * )
     */
    public ?string $uid = null;
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

use OpenApi\Attributes as OA;

final class OpenAPIPropertyExampleKeepNumericString
{
    #[OA\Property(example: '1112')]
    public ?string $uid = null;
}

?>
