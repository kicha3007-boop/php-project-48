<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $content, string $dataType): array
{
    return match (strtolower($dataType)) {
        'json' => json_decode($content, true, flags: JSON_THROW_ON_ERROR),
        'yml', 'yaml' => Yaml::parse($content),
        default => throw new \InvalidArgumentException("Unsupported data type '{$dataType}'"),
    };
}
