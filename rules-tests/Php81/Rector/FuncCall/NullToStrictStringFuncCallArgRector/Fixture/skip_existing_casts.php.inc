<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class DemoFile
{
    public function run()
    {
        return $relpath
            . '/' . rawurlencode((string) $this->Get('account_code') ?: $this->Get('customer_id'));
    }

    public function Get($fieldname)
    {
        return $this->{$fieldname} ?? null;
    }
}
?>
