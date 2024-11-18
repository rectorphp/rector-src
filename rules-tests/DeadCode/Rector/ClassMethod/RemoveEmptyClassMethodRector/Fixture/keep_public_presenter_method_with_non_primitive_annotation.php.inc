<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Fixture;

use Nette\Application\UI\Presenter;
use Nette\Security\User;

final class KeepPublicPresenterMethodWithoutPrimitiveAnnotation extends Presenter
{
    /**
     * @User(secured)
     */
    public function keep()
    {
    }
}
