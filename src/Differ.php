<?php

namespace Differ\Differ;

use function Differ\Parsers\parse;
use function Differ\DiffTree\buildDiffTree;
use function Differ\Formatters\format;

function genDiff(string $firstPath, string $secondPath, string $formatName = 'stylish'): string
{
    $firstData = readData($firstPath);
    $secondData = readData($secondPath);
    $diffTree = buildDiffTree($firstData, $secondData);

    return format($diffTree, $formatName);
}

function readData(string $path): array
{
    $absolutePath = realpath($path);
    if ($absolutePath === false) {
        throw new \RuntimeException("File '{$path}' does not exist");
    }

    $content = file_get_contents($absolutePath);
    $dataType = pathinfo($absolutePath, PATHINFO_EXTENSION);

    return parse($content, $dataType);
}
