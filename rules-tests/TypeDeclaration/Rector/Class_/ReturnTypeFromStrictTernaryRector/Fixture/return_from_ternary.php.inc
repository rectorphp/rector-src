<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture;

class Appointment
{
    private function find_one(): self|false {
        return false;
    }

    public function getRent()
    {
        $rent = $this->find_one();

        return $rent ?: null;
    }

}
?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture;

class Appointment
{
    private function find_one(): self|false {
        return false;
    }

    public function getRent(): ?\Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture\Appointment
    {
        $rent = $this->find_one();

        return $rent ?: null;
    }

}
?>
