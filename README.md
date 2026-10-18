# gendiff — вычислитель отличий

[![hexlet-check](https://github.com/kicha3007-boop/php-project-48/actions/workflows/hexlet-check.yml/badge.svg)](https://github.com/kicha3007-boop/php-project-48/actions/workflows/hexlet-check.yml)
[![PHP CI](https://github.com/kicha3007-boop/php-project-48/actions/workflows/workflow.yml/badge.svg)](https://github.com/kicha3007-boop/php-project-48/actions/workflows/workflow.yml)

Утилита и библиотека, которая сравнивает два конфигурационных файла и показывает разницу между ними.

- Входные форматы: JSON, YAML (`.yml`, `.yaml`)
- Форматы вывода: `stylish` (по умолчанию), `plain`, `json`

## Требования

- PHP 8.3+ (для покрытия — Xdebug)
- Composer

## Установка

```bash
git clone https://github.com/kicha3007-boop/php-project-48.git
cd php-project-48
make install
```

## Использование

```text
$ bin/gendiff -h
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help                     Show this screen
  -v --version                  Show version
  --format <fmt>                Report format [default: stylish]
```

### stylish

```text
$ bin/gendiff tests/fixtures/file1.json tests/fixtures/file2.json
{
    common: {
      + follow: false
        setting1: Value 1
      - setting2: 200
      - setting3: true
      + setting3: null
  ...
```

### plain

```text
$ bin/gendiff --format plain tests/fixtures/file1.yml tests/fixtures/file2.yaml
Property 'common.follow' was added with value: false
Property 'common.setting2' was removed
Property 'common.setting3' was updated. From true to null
...
```

### json

```bash
bin/gendiff --format json tests/fixtures/file1.json tests/fixtures/file2.json
```

### Как библиотека

```php
use function Differ\Differ\genDiff;

$diff = genDiff($pathToFile1, $pathToFile2, 'plain');
```

## Разработка

```bash
make lint            # PHP_CodeSniffer, PSR-12
make test            # PHPUnit
make test-coverage   # покрытие с порогом COVERAGE_MIN из Makefile
```
