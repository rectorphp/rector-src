<?php

declare(strict_types=1);

namespace Rector\Tests\Parallel\ValueObject;

use Clue\React\NDJson\Decoder;
use Clue\React\NDJson\Encoder;
use PHPUnit\Framework\Attributes\RequiresFunction;
use PHPUnit\Framework\TestCase;
use React\EventLoop\StreamSelectLoop;
use React\Stream\DuplexResourceStream;
use Rector\Parallel\ValueObject\ParallelProcess;
use Throwable;

#[RequiresFunction('posix_kill')]
final class ParallelProcessTest extends TestCase
{
    private StreamSelectLoop $streamSelectLoop;

    private string $pidFile;

    /**
     * @var string[]
     */
    private array $errorMessages = [];

    private bool $hasExited = false;

    /**
     * @var resource|null
     */
    private $workerSocket;

    protected function setUp(): void
    {
        $this->streamSelectLoop = new StreamSelectLoop();
        $this->pidFile = (string) tempnam(sys_get_temp_dir(), 'rector_parallel_process_test');
    }

    protected function tearDown(): void
    {
        unlink($this->pidFile);

        if (is_resource($this->workerSocket)) {
            fclose($this->workerSocket);
        }
    }

    public function testQuitTerminatesWorkerThatIsNotConnected(): void
    {
        $parallelProcess = $this->startWorker();

        $parallelProcess->quit();

        $this->assertWorkerIsTerminated();
        $this->assertSame([], $this->errorMessages);
    }

    public function testTimeoutTerminatesWorker(): void
    {
        $parallelProcess = $this->startWorker();
        $this->bindConnection($parallelProcess);

        $parallelProcess->request([]);

        $this->assertWorkerIsTerminated();
        $this->assertSame(['Child process timed out after 1 seconds'], $this->errorMessages);
    }

    public function testQuitKeepsTimeoutOfBusyWorker(): void
    {
        $parallelProcess = $this->startWorker();
        $this->bindConnection($parallelProcess);
        $parallelProcess->request([]);

        $parallelProcess->quit();

        $this->assertWorkerIsTerminated();
        $this->assertSame(['Child process timed out after 1 seconds'], $this->errorMessages);
    }

    /**
     * Starts a worker that neither connects nor answers, like one that is still booting or stuck in an endless loop
     */
    private function startWorker(): ParallelProcess
    {
        $command = sprintf(
            '%s -r %s -- %s',
            escapeshellarg(PHP_BINARY),
            escapeshellarg('file_put_contents($argv[1], getmypid()); sleep(10);'),
            escapeshellarg($this->pidFile)
        );

        $parallelProcess = new ParallelProcess($command, $this->streamSelectLoop, 1);
        $parallelProcess->start(
            static function (): void {
            },
            function (Throwable $throwable): void {
                $this->errorMessages[] = $throwable->getMessage();
            },
            function (): void {
                $this->hasExited = true;
                $this->streamSelectLoop->stop();
            }
        );

        $this->runLoopUntil(fn (): bool => $this->readWorkerPid() > 0);

        return $parallelProcess;
    }

    private function bindConnection(ParallelProcess $parallelProcess): void
    {
        $sockets = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, STREAM_IPPROTO_IP);
        $this->assertIsArray($sockets);

        // the other end stays open but silent, like a connected worker that never answers
        [$this->workerSocket, $mainSocket] = $sockets;

        $duplexResourceStream = new DuplexResourceStream($mainSocket, $this->streamSelectLoop);
        $parallelProcess->bindConnection(new Decoder($duplexResourceStream, true), new Encoder($duplexResourceStream));
    }

    private function assertWorkerIsTerminated(): void
    {
        $this->runLoopUntil(fn (): bool => $this->hasExited);

        $this->assertTrue($this->hasExited);

        // the worker itself must be gone, not only a shell wrapping it
        $this->assertFalse(posix_kill($this->readWorkerPid(), 0));
    }

    /**
     * @param callable(): bool $condition
     */
    private function runLoopUntil(callable $condition): void
    {
        $periodicTimer = $this->streamSelectLoop->addPeriodicTimer(0.05, function () use ($condition): void {
            if ($condition()) {
                $this->streamSelectLoop->stop();
            }
        });

        $timeoutTimer = $this->streamSelectLoop->addTimer(5, function (): void {
            $this->streamSelectLoop->stop();
        });

        $this->streamSelectLoop->run();

        $this->streamSelectLoop->cancelTimer($periodicTimer);
        $this->streamSelectLoop->cancelTimer($timeoutTimer);
    }

    private function readWorkerPid(): int
    {
        clearstatcache();

        return (int) file_get_contents($this->pidFile);
    }
}
