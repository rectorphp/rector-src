<?php

class ApplyOnlyToPrivateConstWhenPublicAvailable
{
    private const A = 1000;
    public const B = 'foo';
    public const C = 'bar';
}
?>
-----
<?php

class ApplyOnlyToPrivateConstWhenPublicAvailable
{
    private const int A = 1000;
    public const B = 'foo';
    public const C = 'bar';
}
?>
