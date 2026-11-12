<?php

declare(strict_types=1);

namespace Rector\Parallel\Stream;

use php_user_filter;

/**
 * Moves what is written to a stream into the file given as the filter parameter, instead of the stream.
 *
 * A forked worker shares the stderr of the main process and PHP cannot redirect it, so this is attached
 * to STDERR to collect the output the same way a spawned worker has its stderr captured.
 *
 * @see \Rector\Parallel\ValueObject\ParallelProcess::runForkedWorker()
 */
final class StderrToFileFilter extends php_user_filter
{
    public const string NAME = 'rector.stderr_to_file';

    /**
     * @param resource $in
     * @param resource $out
     * @param int|null $consumed
     */
    public function filter($in, $out, &$consumed, bool $closing): int
    {
        while ($bucket = stream_bucket_make_writeable($in)) {
            $consumed += $bucket->datalen;
            file_put_contents((string) $this->params, $bucket->data, FILE_APPEND);
        }

        // nothing is passed on to the stream itself
        return PSFS_FEED_ME;
    }
}
