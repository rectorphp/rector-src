<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddArrayReturnDocTypeRector\Fixture;

class BaseFind
{
    /**
     * @return static|false
     */
    public function find()
    {
    }    
}

class Finder extends BaseFind
{
    public function something()
    {
        return $this->find();
    }   
}
?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddArrayReturnDocTypeRector\Fixture;

class BaseFind
{
    /**
     * @return static|false
     */
    public function find()
    {
    }    
}

class Finder extends BaseFind
{
    /**
     * @return static|false
     */
    public function something()
    {
        return $this->find();
    }   
}
