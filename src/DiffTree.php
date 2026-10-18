<?php

namespace Differ\DiffTree;

/**
 * Дерево различий: по узлу на каждый ключ объединения, ключи по алфавиту.
 * Типы узлов: added, removed, changed, unchanged, nested.
 */
function buildDiffTree(array $first, array $second): array
{
    $keys = array_unique([...array_keys($first), ...array_keys($second)]);
    $sortedKeys = sortValues($keys);

    return array_map(
        fn($key) => buildNode($key, $first, $second),
        $sortedKeys
    );
}

function buildNode(string|int $key, array $first, array $second): array
{
    if (!array_key_exists($key, $second)) {
        return ['key' => $key, 'type' => 'removed', 'value' => $first[$key]];
    }
    if (!array_key_exists($key, $first)) {
        return ['key' => $key, 'type' => 'added', 'value' => $second[$key]];
    }

    $firstValue = $first[$key];
    $secondValue = $second[$key];

    if (isStructure($firstValue) && isStructure($secondValue)) {
        return ['key' => $key, 'type' => 'nested', 'children' => buildDiffTree($firstValue, $secondValue)];
    }
    if ($firstValue === $secondValue) {
        return ['key' => $key, 'type' => 'unchanged', 'value' => $firstValue];
    }

    return ['key' => $key, 'type' => 'changed', 'value1' => $firstValue, 'value2' => $secondValue];
}

function isStructure(mixed $value): bool
{
    return is_array($value) && !array_is_list($value);
}

function sortValues(array $values): array
{
    // sort() меняет массив на месте, а $values — копия аргумента
    sort($values, SORT_STRING);

    return $values;
}
