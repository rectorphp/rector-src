<?php

declare(strict_types=1);

namespace Rector\RectorCompatTests\Tests\PHPStan\CustomPHPStanRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Rector\RectorCompatTests\PHPStan\CustomPHPStanRule;

final class CustomPHPStanRuleTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse([__DIR__ . '/Fixture/NonFinalClass.php'], [
            [sprintf(CustomPHPStanRule::ERROR_MESSAGE, "NonFinalClass"), 7]
        ]);
    }

    protected function getRule(): Rule
    {
        return new CustomPHPStanRule();
    }
}
