<?php

use Rector\Core\Exception\ShouldNotHappenException;

final class SkipException
{
    public function getValues()
    {
        throw new ShouldNotHappenException();
    }
}
-----
<?php

use Rector\Core\Exception\ShouldNotHappenException;

final class SkipException
{
    public function getValues(): void
    {
        throw new ShouldNotHappenException();
    }
}
