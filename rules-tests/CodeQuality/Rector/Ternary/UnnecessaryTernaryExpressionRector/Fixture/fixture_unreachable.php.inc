<?php

if (true) {
    return;
}

echo 'test';

class A
{
    public function run($order, $oldOrder)
    {
        return $order || $oldOrder ? true : false;
    }
}

$order || $oldOrder ? true : false;

class B
{
    public function run($order, $oldOrder)
    {
        return $order || $oldOrder ? true : false;
    }
}

?>
-----
<?php

if (true) {
    return;
}

echo 'test';

class A
{
    public function run($order, $oldOrder)
    {
        return $order || $oldOrder;
    }
}

$order || $oldOrder;

class B
{
    public function run($order, $oldOrder)
    {
        return $order || $oldOrder;
    }
}

?>
