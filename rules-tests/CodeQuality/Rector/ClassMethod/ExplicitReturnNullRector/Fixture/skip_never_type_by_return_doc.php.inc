<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

/**
 * @return never
 */
function abort(int $code)
{
    throw new NotFoundException();
}

final class SkipNeverTypeInCatch
{
    public function download(Upload $file, array $headers = [])
    {
        try {
            try {
                return $this->storage->download($file, $headers);
            } catch (RemoteFileNotFoundException $e) {
                return $this->localStorage->download($file, $headers);
            }
        } catch (FileNotFoundException $e) {
            abort(404);
        }
    }
}
