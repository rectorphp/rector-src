<?php

if (true) {
    return;
}

echo 'test';

class FixtureUnreachableA
{
    public function run($order, $oldOrder)
    {
        return $order || $oldOrder ? true : false;
    }
}

$order || $oldOrder ? true : false;

class FixtureUnreachableB
{
    public function run($order, $oldOrder)
    {
        return $order || $oldOrder ? true : false;
    }
}

$order || $oldOrder ? true : false;

?>
-----
<?php

if (true) {
    return;
}

echo 'test';

class FixtureUnreachableA
{
    public function run($order, $oldOrder)
    {
        return $order || $oldOrder;
    }
}

$order || $oldOrder;

class FixtureUnreachableB
{
    public function run($order, $oldOrder)
    {
        return $order || $oldOrder;
    }
}

$order || $oldOrder;

?>
