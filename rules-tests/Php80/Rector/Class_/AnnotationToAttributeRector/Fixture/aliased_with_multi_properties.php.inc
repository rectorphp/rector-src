<?php

declare(strict_types=1);

namespace OpenApi\Attributes
{
    use OpenApi\Annotations as OA;

    class Property {}

    class TestClass
    {
        /**
         * @OA\Property(type="string")
         */
        public $property1;

        /**
         * @OA\Property(type="string")
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

    class Property {}

    class TestClass
    {
        #[OA\Property(type: 'string')]
        public $property1;

        #[OA\Property(type: 'string')]
        public $property2;
    }

}

?>
