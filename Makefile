# Порог покрытия: ниже него `make test-coverage` падает, а с ним и сборка в CI
COVERAGE_MIN ?= 90

install:
	composer install

validate:
	composer validate

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin tests

lint-fix:
	composer exec --verbose phpcbf -- --standard=PSR12 src bin tests

test:
	composer exec --verbose phpunit tests

test-coverage:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-clover=build/logs/clover.xml
	@php -r '$$m = simplexml_load_file("build/logs/clover.xml")->project->metrics; $$total = (int) $$m["statements"]; $$covered = (int) $$m["coveredstatements"]; $$pct = $$total > 0 ? $$covered / $$total * 100 : 100.0; printf("Total coverage: %.2f%% (min %d%%)\n", $$pct, $(COVERAGE_MIN)); exit($$pct + 1e-9 < $(COVERAGE_MIN) ? 1 : 0);'

.PHONY: install validate lint lint-fix test test-coverage
