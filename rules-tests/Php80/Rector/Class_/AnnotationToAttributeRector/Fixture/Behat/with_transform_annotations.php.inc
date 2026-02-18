<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Behat;

final class TransformMethod
{
    /**
     * @Transform :user
     */
    public function transformUser($user)
    {
        return $user;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Behat;

final class TransformMethod
{
    #[\Behat\Transformation\Transform(':user')]
    public function transformUser($user)
    {
        return $user;
    }
}

?>
