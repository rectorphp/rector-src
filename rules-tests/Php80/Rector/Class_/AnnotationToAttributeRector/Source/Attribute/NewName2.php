<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Attribute;

use Attribute;

#[Attribute]
final class NewName2
{
	public function __construct(
		protected string $action,
	) {
	}
}
