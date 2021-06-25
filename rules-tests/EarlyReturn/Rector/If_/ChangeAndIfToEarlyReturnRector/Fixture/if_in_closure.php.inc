<?php

namespace Rector\Tests\EarlyReturn\Rector\If_\ChangeAndIfToEarlyReturnRector\Fixture;

class IfInClosure
{
    public function run()
    {
        Text::make(___('Type'), function () {
            if ($this->_fieldType_ === 'wire' && $this->users->getRole() === 'correct-role') {
                return ___('Found');
            }
            return Arr::get(Purses::getOptions(), $this->_fieldType_) ?? $this->_fieldType_;
        });
    }
}

?>
-----
<?php

namespace Rector\Tests\EarlyReturn\Rector\If_\ChangeAndIfToEarlyReturnRector\Fixture;

class IfInClosure
{
    public function run()
    {
        Text::make(___('Type'), function () {
            if ($this->_fieldType_ !== 'wire') {
                return Arr::get(Purses::getOptions(), $this->_fieldType_) ?? $this->_fieldType_;
            }
            if ($this->users->getRole() !== 'correct-role') {
                return Arr::get(Purses::getOptions(), $this->_fieldType_) ?? $this->_fieldType_;
            }
            return ___('Found');
        });
    }
}

?>
