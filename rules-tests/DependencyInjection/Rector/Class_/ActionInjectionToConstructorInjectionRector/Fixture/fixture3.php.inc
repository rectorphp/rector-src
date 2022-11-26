<?php

namespace Rector\Tests\DependencyInjection\Rector\Class_\ActionInjectionToConstructorInjectionRector\Fixture;

use Rector\Tests\DependencyInjection\Rector\Class_\ActionInjectionToConstructorInjectionRector\Source\CategoryRepository;
use Rector\Tests\DependencyInjection\Rector\Class_\ActionInjectionToConstructorInjectionRector\Source\ProductRepository;
use Rector\Tests\DependencyInjection\Rector\Class_\ActionInjectionToConstructorInjectionRector\Source\SomeProduct;

abstract class ParentFixture3Constructor {
    public function __construct(protected SomeProduct $someProduct)
    {
    }
}

final class Fixture3Controller extends ParentFixture3Constructor
{
    public function default(CategoryRepository $categoryRepository, ProductRepository $productRepository)
    {
        $products = $productRepository->fetchAll();
        $categories = $categoryRepository->fetchAll();
    }
}

?>
-----
<?php

namespace Rector\Tests\DependencyInjection\Rector\Class_\ActionInjectionToConstructorInjectionRector\Fixture;

use Rector\Tests\DependencyInjection\Rector\Class_\ActionInjectionToConstructorInjectionRector\Source\CategoryRepository;
use Rector\Tests\DependencyInjection\Rector\Class_\ActionInjectionToConstructorInjectionRector\Source\ProductRepository;
use Rector\Tests\DependencyInjection\Rector\Class_\ActionInjectionToConstructorInjectionRector\Source\SomeProduct;

abstract class ParentFixture3Constructor {
    public function __construct(protected SomeProduct $someProduct)
    {
    }
}

final class Fixture3Controller extends ParentFixture3Constructor
{
    public function __construct(\Rector\Tests\DependencyInjection\Rector\Class_\ActionInjectionToConstructorInjectionRector\Source\SomeProduct $someProduct, private \Rector\Tests\DependencyInjection\Rector\Class_\ActionInjectionToConstructorInjectionRector\Source\CategoryRepository $categoryRepository, private \Rector\Tests\DependencyInjection\Rector\Class_\ActionInjectionToConstructorInjectionRector\Source\ProductRepository $productRepository)
    {
        parent::__construct($someProduct);
    }
    public function default()
    {
        $products = $this->productRepository->fetchAll();
        $categories = $this->categoryRepository->fetchAll();
    }
}

?>
