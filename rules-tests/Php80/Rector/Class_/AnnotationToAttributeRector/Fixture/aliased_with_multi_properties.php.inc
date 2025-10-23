<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Attribute\OpenApi\Attribute
{
    use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Attribute\OpenApi\Annotation as OA;

    class TestClass
    {
        /**
         * @OA\SomeProperty(type="string")
         */
        public $property1;

        /**
         * @OA\SomeProperty(type="string")
         */
        public $property2;
    }

}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Attribute\OpenApi\Attribute
{
    use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Attribute\OpenApi\Attribute as OA;

    class TestClass
    {
        #[OA\SomeProperty(type: 'string')]
        public $property1;

        #[OA\SomeProperty(type: 'string')]
        public $property2;
    }

}

?>