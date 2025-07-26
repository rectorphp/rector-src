<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class WithExistingCastOnTernary
{
    public function run()
    {
        return $relpath
            . '/' . rawurlencode((string) $this->Get('account_code') ?: $this->Get('customer_id'));
    }

    public function run2()
    {
        return $relpath
            . '/' . rawurlencode((string) $this->Get('account_code') ?: null);
    }

    public function Get($fieldname)
    {
        return $this->{$fieldname} ?? null;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class WithExistingCastOnTernary
{
    public function run()
    {
        return $relpath
            . '/' . rawurlencode((string) $this->Get('account_code') ?: (string) $this->Get('customer_id'));
    }

    public function run2()
    {
        return $relpath
            . '/' . rawurlencode((string) $this->Get('account_code') ?: '');
    }

    public function Get($fieldname)
    {
        return $this->{$fieldname} ?? null;
    }
}

?>
