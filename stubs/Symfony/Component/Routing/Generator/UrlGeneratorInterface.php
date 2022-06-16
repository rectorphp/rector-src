<?php

declare(strict_types=1);

namespace Symfony\Component\Routing\Generator;

if (interface_exists('Symfony\Component\Routing\Generator\UrlGeneratorInterface')) {
    return;
}

interface UrlGeneratorInterface
{
    /**
     * Generates an absolute URL, e.g. "http://example.com/dir/file".
     */
    public const ABSOLUTE_URL = true;

    /**
     * Generates an absolute path, e.g. "/dir/file".
     */
    public const ABSOLUTE_PATH = false;

    /**
     * Generates a relative path based on the current request path, e.g. "../parent-file".
     *
     * @see UrlGenerator::getRelativePath()
     */
    public const RELATIVE_PATH = 'relative';

    /**
     * Generates a network path, e.g. "//example.com/dir/file".
     * Such reference reuses the current scheme but specifies the host.
     */
    public const NETWORK_PATH = 'network';

    public function generate($name, $parameters = array(), $referenceType = self::ABSOLUTE_PATH);
}
