<?php

declare(strict_types=1);

namespace OpenApi\Attributes
{
    use OpenApi\Annotations as OA;

    #[\Attribute]
    class SomeProperty {}

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

namespace OpenApi\Attributes
{
    use OpenApi\Attributes as OA;

    #[\Attribute]
    class SomeProperty {}

    class TestClass
    {
        #[OA\SomeProperty(type: 'string')]
        public $property1;

        #[OA\SomeProperty(type: 'string')]
        public $property2;
    }

}

?>