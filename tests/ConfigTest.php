<?php

use Mateodioev\StringVars\{Config, StringMatcherException};
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testCreateConfig(): void
    {
        $this->assertInstanceOf(
            Config::class,
            new Config
        );
    }

    public function testThrowExceptionAddingExistingFormat(): void
    {
        $this->expectException(StringMatcherException::class);

        $cfg = new Config;
        $cfg->addFormat('all', '(.*)'); // throws exception here
    }

    public function testBuildConfigRegex(): void
    {
        $cfg = new Config;

        $this->assertEquals(
            '([a-zA-Z\d]+)',
            $cfg->build('{w:name}')
        );

        $this->assertEquals(
            '([\d]+)',
            $cfg->build('{d:age}')
        );

        $this->assertEquals(
            '([\d]+(?:\.[\d]+)?)',
            $cfg->build('{f:price}')
        );

        $this->assertEquals(
            '([\s\S]+)',
            $cfg->build('{all:desc}')
        );

        $this->assertEquals(
            '([^/]+)',
            $cfg->build('{slug}')
        );

        $this->assertEquals(
            '([^/]+)/([\d]+)?',
            $cfg->build('{slug}/{d:age}?')
        );
    }
}