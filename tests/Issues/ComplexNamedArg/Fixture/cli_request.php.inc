<?php

namespace Rector\Core\Tests\Issues\ComplexNamedArg\Fixture;

use Symfony\Component\HttpFoundation\Request;

final class CliRequest extends Request
{
    public function __construct(
        public readonly TraceableCommand $command,
    ) {
        parent::__construct(
            attributes: ['_controller' => \get_class($command->command), '_virtual_type' => 'command'],
            server: $_SERVER,
        );
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\ComplexNamedArg\Fixture;

use Symfony\Component\HttpFoundation\Request;

final class CliRequest extends Request
{
    /**
     * @readonly
     * @var \Rector\Core\Tests\Issues\ComplexNamedArg\Fixture\TraceableCommand
     */
    public $command;
    public function __construct(TraceableCommand $command)
    {
        $this->command = $command;
        parent::__construct([], [], ['_controller' => \get_class($command->command), '_virtual_type' => 'command'], [], [], $_SERVER);
    }
}

?>