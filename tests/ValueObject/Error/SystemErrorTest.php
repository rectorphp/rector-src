<?php

declare(strict_types=1);

namespace Rector\Tests\ValueObject\Error;

use PHPUnit\Framework\TestCase;
use Rector\Php80\Rector\Identical\StrStartsWithRector;
use Rector\ValueObject\Error\SystemError;

final class SystemErrorTest extends TestCase
{
    public function testGetAbsoluteFilePath(): void
    {
        $systemError = new SystemError('Some error message', __DIR__ . '/SystemErrorTest.php');
        $this->assertSame(realpath(__DIR__ . '/SystemErrorTest.php'), $systemError->getAbsoluteFilePath());
    }

    public function testGetAbsoluteFilePathShouldReturnNullWhenRelativeFilePathIsNull(): void
    {
        $systemError = new SystemError('Some error message');
        $this->assertNull($systemError->getAbsoluteFilePath());
    }

    public function testGetRectorShortClass(): void
    {
        $systemError = new SystemError('Some error message', null, 1, StrStartsWithRector::class);
        $this->assertSame('StrStartsWithRector', $systemError->getRectorShortClass());
    }

    public function testGetRectorShortClassShouldReturnNullWhenRectorClassIsNull(): void
    {
        $systemError = new SystemError('Some error message');
        $this->assertNull($systemError->getRectorShortClass());
    }
}
