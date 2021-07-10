<?php

namespace Rector\Tests\Defluent\Rector\ClassMethod\ReturnThisRemoveRector\Fixture;

class SkipMagicSet
{
    public function setAuthor(string $author): self
    {
        return $this;
    }

    public function setTitle(string $author): self
    {
        return $this;
    }

    /**
     * @return $this
     */
    public function __set(string $key, $value = null)
    {
        $method = 'set' . $key;
        if (method_exists($this, $method)) {
            $this->{$key} = $value;
        }

        return $this;
    }
}

$obj = new SkipMagicSet();
$obj->setAuthor('foo')->setTitle('bar');

?>