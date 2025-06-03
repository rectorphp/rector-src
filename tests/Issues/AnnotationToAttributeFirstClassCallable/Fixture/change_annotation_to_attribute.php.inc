<?php

namespace Symfony\Component\Serializer\Annotation
{

    #[\Attribute]
    class Context {}


    class Post
    {
        private function __construct(
            /**
             * @Context(normalizationContext={"groups": "public"})
             */
            public array $images,
        ) {
        }
    }

}

?>
-----
<?php

namespace Symfony\Component\Serializer\Annotation
{

    #[\Attribute]
    class Context {}


    class Post
    {
        private function __construct(
            #[Context(normalizationContext: ['groups' => 'public'])]
            public array $images,
        ) {
        }
    }

}

?>