<?php

namespace Differ\Formatters\Plain;

function render(array $diffTree): string
{
    return implode("\n", renderNodes($diffTree, []));
}

function renderNodes(array $nodes, array $parentPath): array
{
    $lines = array_map(fn(array $node) => renderNode($node, $parentPath), $nodes);

    return array_merge(...$lines);
}

function renderNode(array $node, array $parentPath): array
{
    $path = [...$parentPath, $node['key']];
    $property = implode('.', $path);

    return match ($node['type']) {
        'nested' => renderNodes($node['children'], $path),
        'unchanged' => [],
        'added' => ["Property '{$property}' was added with value: " . toString($node['value'])],
        'removed' => ["Property '{$property}' was removed"],
        'changed' => [
            sprintf(
                "Property '%s' was updated. From %s to %s",
                $property,
                toString($node['value1']),
                toString($node['value2'])
            ),
        ],
    };
}

function toString(mixed $value): string
{
    return match (true) {
        is_array($value) => '[complex value]',
        $value === null => 'null',
        is_bool($value) => $value ? 'true' : 'false',
        is_string($value) => "'{$value}'",
        default => (string) $value,
    };
}
