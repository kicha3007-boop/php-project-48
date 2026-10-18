<?php

namespace Differ\Formatters\Stylish;

const INDENT_SIZE = 4;
const SIGN_OFFSET = 2;

function render(array $diffTree): string
{
    return renderNodes($diffTree, 1);
}

function renderNodes(array $nodes, int $depth): string
{
    $lines = array_map(fn(array $node) => renderNode($node, $depth), $nodes);

    return wrapInBraces($lines, $depth);
}

function renderNode(array $node, int $depth): string
{
    $key = $node['key'];

    return match ($node['type']) {
        'nested' => renderLine(' ', $key, renderNodes($node['children'], $depth + 1), $depth),
        'unchanged' => renderLine(' ', $key, stringify($node['value'], $depth), $depth),
        'added' => renderLine('+', $key, stringify($node['value'], $depth), $depth),
        'removed' => renderLine('-', $key, stringify($node['value'], $depth), $depth),
        'changed' => implode("\n", [
            renderLine('-', $key, stringify($node['value1'], $depth), $depth),
            renderLine('+', $key, stringify($node['value2'], $depth), $depth),
        ]),
    };
}

function renderLine(string $sign, string|int $key, string $value, int $depth): string
{
    $indent = str_repeat(' ', $depth * INDENT_SIZE - SIGN_OFFSET);

    return "{$indent}{$sign} {$key}: {$value}";
}

function wrapInBraces(array $lines, int $depth): string
{
    $closingIndent = str_repeat(' ', ($depth - 1) * INDENT_SIZE);
    $body = implode("\n", $lines);

    return "{\n{$body}\n{$closingIndent}}";
}

function stringify(mixed $value, int $depth): string
{
    if (!is_array($value)) {
        return toString($value);
    }

    $lines = array_map(
        fn($key, $item) => renderLine(' ', $key, stringify($item, $depth + 1), $depth + 1),
        array_keys($value),
        $value
    );

    return wrapInBraces($lines, $depth + 1);
}

function toString(mixed $value): string
{
    return match (true) {
        $value === null => 'null',
        is_bool($value) => $value ? 'true' : 'false',
        default => (string) $value,
    };
}
