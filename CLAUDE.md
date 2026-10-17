# CLAUDE.md – theme-bench

Benchmark of [wp-app-framework](https://github.com/webcodingcat/wp-app-framework): a theme with the framework (slug `theme-bench`, namespace `bench`, PHP `WebCodingCat\ThemeBench\`), the same theme without it (`baseline/`), a plugin with a framework instance (`bench-plugin/`, namespace `bench-plugin`, PHP `WebCodingCat\BenchPlugin\`), a timer plugin (`timer/`) and the script `bin/bench` (measurement, `--dev`, `--profile`, `--coverage`). The README explains scenarios, phases and options. Framework principles, conventions and testing rules: the [developer documentation](https://webcodingcat.github.io/wp-app-framework/next/development/) (in the framework checkout `docs/content/development/`) and the framework's CLAUDE.md.

## Rules

- The two themes must stay comparable: the baseline is built from `baseline/style.css` plus the `theme.json` and `templates/` of the theme; never add styles, templates or code to only one of them.
- The benchmark measures the framework, not the theme: blocks and services stay small and typical. Every area of the framework that runs on requests has a scenario; a new framework feature gets a block or service and a scenario of its own (check with `--coverage`), and the existing ones stay so reports stay comparable.
- `bench-plugin/` is a project of its own: its files are `export-ignore` here, and `bin/bench` releases it from a copy with its own git repository. The theme does not require it with Composer; PHPStan scans its `src/` for the public API the theme uses.
- Measure in release mode with Xdebug off (the default of `bin/bench`); `--dev` and the Xdebug modes give other numbers. Do not run other tests of wp-test-env while measuring.
- Reports in `reports/` are not committed; the results of a run worth keeping go into the step archive of the plan.
- Class properties are never `public` (exceptions: block attribute and value classes); everything in this repository is English; other repositories are linked by GitHub URL.

## Commands

Run from this directory, with [wp-test-env](https://github.com/webcodingcat/wp-test-env) checked out next to it; every check must pass without errors or warnings.

```sh
../wp-test-env/bin/prepare --link webcodingcat/wp-app-framework=wp-app-framework theme-bench
../wp-test-env/bin/tools -w theme-bench composer build-manifest
../wp-test-env/bin/tools -w theme-bench composer lint   # PHPStan (max) and PHP-CS-Fixer, also bin/ and timer/
bin/bench [--skip-build] [--keep] [--dev] [--profile | --coverage]   # instance "bench" of wp-test-env, removed afterwards
docker run --rm -v "$PWD:/mnt" -w /mnt koalaman/shellcheck:stable bin/bench
```
