<?php

/** @changelog https://github.com/symfony/symfony/blob/master/src/Symfony/Component/Routing/Annotation/Route.php */

declare(strict_types=1);

namespace Symfony\Component\Serializer\Attribute;

if (class_exists('Symfony\Component\Serializer\Attribute\Groups')) {
    return;
}

#[\Attribute]
class Groups
{
}
