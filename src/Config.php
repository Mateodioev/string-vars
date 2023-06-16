<?php

namespace Mateodioev\StringVars;

use function in_array, array_keys, array_map, array_values, preg_replace;

class Config
{
    public function __construct(
        protected array $formats = []
    ) {
        $this->formats = [
            ...[
                'w'   => '([a-zA-Z\d]+)',         // All strings
                'd'   => '([\d]+)',               // All digits
                'f'   => '([\d]+(?:\.[\d]+)?)',   // All floats
                'all' => '([\s\S]+)',             // All strings and digits
                ''    => '([^/]+)'                // All strings except /
            ],
            ...$this->formats
        ];
    }

    public function addFormat(string $key, string $regexFormat): static
    {
        if (in_array($key, array_keys($this->formats))) {
            throw new StringMatcherException("Format key '$key' already exists");
        }

        $this->formats[$key] = $regexFormat;
        return $this;
    }

    public function getFormat(string $format)
    {
        return preg_replace(
            $this->patterns(),
            $this->replacements(),
            $format
        );
    }

    /**
     * @return array<string>
     */
    private function patterns(): array
    {
        return array_map(function ($key) {
            if ($key !== '') {
                return '/{(' . $key . ':\w+)}/i';
            }
            return '/{(\w+)}/i';
        }, array_keys($this->formats));
    }

    private function replacements(): array
    {
        return array_values($this->formats);
    }
}
