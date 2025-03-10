<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Attribute;

use Attribute;

#[Attribute]
final class NewName1
{
	public function __construct(
		protected int $limit,
		protected int $period,
	) {
	}
}
