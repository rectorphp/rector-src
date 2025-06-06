<?php

/** @changelog https://github.com/symfony/symfony/blob/master/src/Symfony/Component/Routing/Annotation/Route.php */

declare(strict_types=1);

namespace Symfony\Component\Routing\Annotation;

if (class_exists('Symfony\Component\Routing\Annotation\Route')) {
    return;
}

/**
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
#[\Attribute]
class Route
{
    private $path;
    private $localizedPaths = [];
    private $name;
    private $requirements = [];
    private $options = [];
    private $defaults = [];
    private $host;
    private $methods = [];
    private $schemes = [];
    private $condition;
    private $locale;
    private $format;
    private $utf8;

    /**
     * @param array $data An array of key/value parameters
     *
     * @throws \BadMethodCallException
     */
    public function __construct(array $data)
    {
        if (isset($data['localized_paths'])) {
            throw new \BadMethodCallException(sprintf('Unknown property "localized_paths" on annotation "%s".', \get_class($this)));
        }

        if (isset($data['value'])) {
            $data[\is_array($data['value']) ? 'localized_paths' : 'path'] = $data['value'];
            unset($data['value']);
        }

        if (isset($data['path']) && \is_array($data['path'])) {
            $data['localized_paths'] = $data['path'];
            unset($data['path']);
        }

        if (isset($data['locale'])) {
            $data['defaults']['_locale'] = $data['locale'];
            unset($data['locale']);
        }

        if (isset($data['format'])) {
            $data['defaults']['_format'] = $data['format'];
            unset($data['format']);
        }

        if (isset($data['utf8'])) {
            $data['options']['utf8'] = filter_var($data['utf8'], FILTER_VALIDATE_BOOLEAN) ?: false;
            unset($data['utf8']);
        }

        foreach ($data as $key => $value) {
            $method = 'set'.str_replace('_', '', $key);
            if (!method_exists($this, $method)) {
                throw new \BadMethodCallException(sprintf('Unknown property "%s" on annotation "%s".', $key, \get_class($this)));
            }
            $this->$method($value);
        }
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setLocalizedPaths(array $localizedPaths)
    {
        $this->localizedPaths = $localizedPaths;
    }

    public function getLocalizedPaths(): array
    {
        return $this->localizedPaths;
    }

    public function setHost($pattern)
    {
        $this->host = $pattern;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setRequirements($requirements)
    {
        $this->requirements = $requirements;
    }

    public function getRequirements()
    {
        return $this->requirements;
    }

    public function setOptions($options)
    {
        $this->options = $options;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setDefaults($defaults)
    {
        $this->defaults = $defaults;
    }

    public function getDefaults()
    {
        return $this->defaults;
    }

    public function setSchemes($schemes)
    {
        $this->schemes = \is_array($schemes) ? $schemes : [$schemes];
    }

    public function getSchemes()
    {
        return $this->schemes;
    }

    public function setMethods($methods)
    {
        $this->methods = \is_array($methods) ? $methods : [$methods];
    }

    public function getMethods()
    {
        return $this->methods;
    }

    public function setCondition($condition)
    {
        $this->condition = $condition;
    }

    public function getCondition()
    {
        return $this->condition;
    }
}
