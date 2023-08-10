<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Fixture;

enum SkipEnum: string
{
	case EN = 'en';

	public static function ui(): array
	{
		return [
			self::EN,
		];
	}

	public static function dynamicContent(): array
	{
		return self::cases();
	}
}