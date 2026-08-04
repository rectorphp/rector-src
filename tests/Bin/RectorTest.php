<?php

declare(strict_types=1);

namespace Rector\Tests\Bin;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

final class RectorTest extends TestCase
{
    public static function outputProvider()
    {

        yield "Version" => [
            'command'        => 'bin/rector --version',
            'expectedOutput' => "Rector @package_version@\n",
        ];

        yield "Exception with previous console output" => [
            'command'        => 'bin/rector -c tests/Bin/config/incorrect-phpstan-files.php',
            'expectedOutput' => <<<TXT
                
                 [ERROR] Rector\NodeTypeResolver\DependencyInjection\PHPStanServicesFactory     
                
                 [ERROR] Unexpected item 'parameters › invalidParameters'.                      
                
                
                TXT,
        ];
        yield "Exception with previous console output in JSON format" => [
            'command'        => 'bin/rector -c tests/Bin/config/incorrect-phpstan-files.php --output-format json',
            'expectedOutput' => '{"fatal_errors":["Rector\\\\NodeTypeResolver\\\\DependencyInjection\\\\PHPStanServicesFactory","Unexpected item \'parameters › invalidParameters\'."]}'
        ];
    }

    #[DataProvider('outputProvider')]
    public function testConsoleOutput(string $command, string $expectedOutput): void
    {
        $process = Process::fromShellCommandline($command);
        $process->run();
        $this->assertSame($expectedOutput, $process->getOutput());
    }
}
