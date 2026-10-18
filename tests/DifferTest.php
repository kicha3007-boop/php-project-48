<?php

namespace Differ\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    private function getFixturePath(string $name): string
    {
        return __DIR__ . "/fixtures/{$name}";
    }

    public static function diffProvider(): array
    {
        return [
            'json, stylish' => ['file1.json', 'file2.json', 'stylish', 'result_stylish.txt'],
            'yaml, stylish' => ['file1.yml', 'file2.yaml', 'stylish', 'result_stylish.txt'],
            'json, plain' => ['file1.json', 'file2.json', 'plain', 'result_plain.txt'],
            'yaml, plain' => ['file1.yml', 'file2.yaml', 'plain', 'result_plain.txt'],
            'json, json' => ['file1.json', 'file2.json', 'json', 'result_json.json'],
            'yaml, json' => ['file1.yml', 'file2.yaml', 'json', 'result_json.json'],
        ];
    }

    #[DataProvider('diffProvider')]
    public function testGenDiff(string $first, string $second, string $format, string $expected): void
    {
        $diff = genDiff($this->getFixturePath($first), $this->getFixturePath($second), $format);

        $this->assertStringEqualsFile($this->getFixturePath($expected), $diff);
    }

    public function testDefaultFormatIsStylish(): void
    {
        $diff = genDiff($this->getFixturePath('file1.json'), $this->getFixturePath('file2.json'));

        $this->assertStringEqualsFile($this->getFixturePath('result_stylish.txt'), $diff);
    }

    public function testJsonFormatIsValidJson(): void
    {
        $diff = genDiff($this->getFixturePath('file1.json'), $this->getFixturePath('file2.yaml'), 'json');

        $this->assertJson($diff);
    }

    public function testUnknownFormat(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        genDiff($this->getFixturePath('file1.json'), $this->getFixturePath('file2.json'), 'xml');
    }

    public function testMissingFile(): void
    {
        $this->expectException(\RuntimeException::class);
        genDiff($this->getFixturePath('missing.json'), $this->getFixturePath('file2.json'));
    }

    public function testUnsupportedDataType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        genDiff($this->getFixturePath('result_plain.txt'), $this->getFixturePath('file2.json'));
    }
}
