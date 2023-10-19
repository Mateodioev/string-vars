<?php

namespace Mateodioev\StringVars;

use function preg_match_all, array_map, count, preg_match, array_shift;

class Matcher
{
    private string $regexFormat = '';
    private string $pattern = '';
    protected array $parameters = [];
    /**
     * format:
     * - {w:name} all strings
     * - {d:name} only integers
     * - {f:name} only double
     * - {name} Match all, / exclude
     * - {a:name} Match all
     *
     * Make any parameter optionally adding "?" after {}
     */
    public function __construct(
        public string $format,
        ?Config $config = null
    ) {
        $config ??= new Config();

        $this->regexFormat = $config->build($this->format);

        $this->setParameters();
    }

    /**
     * Create a new instance
     */
    public static function new(string $format): static
    {
        return new static($format);
    }

    /**
     * Validate if string match with format
     *
     * @param string $string String to validate
     * @param boolean $strict If true, string must be equal to format
     */
    public function isValid(string $string, bool $strict = false): bool
    {
        $this->buildPattern($strict);

        return preg_match($this->pattern, $string) === 1;
    }

    /**
     * Match the string with format
     * @param bool $strict If true, the string must begin and end with the format
     */
    public function match(string $str, bool $strict = false): array
    {
        $this->buildPattern($strict);

        $matches = [];
        preg_match($this->pattern . 'i', $str, $matches);
        array_shift($matches);

        // return \array_combine($this->parameters, $matches); // not work with optional parameters
        $parameters = [];
        foreach ($this->parameters as $i => $parameter) {
            $match = $matches[$i] ?? null;
            $match = $match === '' ? null : $match;
            $parameters[$parameter] = $match;
        }

        return $parameters;
    }

    /**
     * Build pattern from format
     */
    private function buildPattern(bool $strict = false): string
    {
        $this->pattern = '#' . $this->regexFormat . '#';
        if ($strict) $this->pattern = '#^' . $this->regexFormat . '$#';

        return $this->pattern;
    }

    /**
     * Get parameters from format
     */
    private function setParameters(): void
    {
        $regex = '/{([a-z]+)(:([a-z]+))?}/i';
        // get parameters
        preg_match_all($regex, $this->format, $matches, PREG_SET_ORDER);

        // get only name
        $this->parameters = array_map(function ($match) {
            return $match[count($match) - 1];
        }, $matches);
    }
}
