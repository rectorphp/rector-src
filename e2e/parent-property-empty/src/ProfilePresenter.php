<?php

namespace App;

use Nette\Security\Passwords;
use Nette;

abstract class MyBasePresenter extends Nette\Application\UI\Presenter
{
}

class ProfilePresenter extends MyBasePresenter
{
    public function profileFormSucceed(Form $form)
    {
        $this->user->password = Passwords::hash($form->getValues()->password);
    }
}
