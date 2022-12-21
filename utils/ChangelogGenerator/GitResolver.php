<?php

declare(strict_types=1);

namespace Rector\Utils\ChangelogGenerator;

use Rector\Utils\ChangelogGenerator\ValueObject\Commit;
use Symfony\Component\Process\Process;

final class GitResolver
{
    /**
     * @return Commit[]
     */
    public function resolveCommitLinesFromToHashes(string $fromCommit, string $toCommit): array
    {
        $commitHashRange = sprintf('%s..%s', $fromCommit, $toCommit);

        $output = $this->exec(['git', 'log', $commitHashRange, '--reverse', '--pretty=%H %s']);
        $commitLines = explode("\n", $output);

        // remove empty values
        $commitLines = array_filter($commitLines);
        return $this->mapCommitLinesToCommits($commitLines);
    }

    /**
     * @param string[] $commitLines
     * @return Commit[]
     */
    private function mapCommitLinesToCommits(array $commitLines): array
    {
        return array_map(static function (string $line): Commit {
            [$hash, $message] = explode(' ', $line, 2);
            return new Commit($hash, $message);
        }, $commitLines);
    }

    /**
     * @param string[] $commandParts
     */
    private function exec(array $commandParts): string
    {
        $process = new Process($commandParts);
        $process->run();

        return $process->getOutput();
    }
}
