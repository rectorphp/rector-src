<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

final class ArrayWithIndexAssigned
{
    /**
     * @var array<int, string>
     */
    private $exitcodeLabels;

    private int $currentExitCode = 1;

    /**
     * @param array<int, string> $exitcodeLabels
     */
    public function __construct(array $exitcodeLabels)
    {
        $this->exitcodeLabels = $exitcodeLabels;
    }

    public function setMessageForCode(int $exitCode, string $message): void
    {
        $this->exitcodeLabels[$exitCode] = $message;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

final class ArrayWithIndexAssigned
{
    /**
     * @var array<int, string>
     */
    private array $exitcodeLabels;

    private int $currentExitCode = 1;

    /**
     * @param array<int, string> $exitcodeLabels
     */
    public function __construct(array $exitcodeLabels)
    {
        $this->exitcodeLabels = $exitcodeLabels;
    }

    public function setMessageForCode(int $exitCode, string $message): void
    {
        $this->exitcodeLabels[$exitCode] = $message;
    }
}

?>
