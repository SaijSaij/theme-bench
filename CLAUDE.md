# CLAUDE.md – theme-bench

Benchmark of [wp-app-framework](https://github.com/saijsaij/wp-app-framework): a minimal theme with the framework (slug `theme-bench`, namespace `bench`, PHP `Saij\ThemeBench\`), the same theme without it (`baseline/`), a timer plugin (`timer/`) and the script `bin/bench`. The README explains scenarios, phases and options. Framework principles and rules: the framework's CLAUDE.md.

## Rules

- The two themes must stay comparable: the baseline is built from `baseline/style.css` plus the `theme.json` and `templates/` of the theme; never add styles, templates or code to only one of them.
- The benchmark measures the framework, not the theme: blocks stay small and typical (text fields, enum, value objects, a container, a cached block). A new framework feature worth measuring gets a block and a scenario of its own; keep the existing ones so reports stay comparable.
- Measure in the release site (release mode, Xdebug off); the development site (Xdebug, manifest stat checks) gives other numbers.
- Reports in `reports/` are not committed; the results of a run worth keeping go into the step archive of the plan.
- Class properties are never `public` (exceptions: block attribute and value classes); everything in this repository is English; other repositories are linked by GitHub URL.

## Commands

Run from a [wp-dev-env](https://github.com/saijsaij/wp-dev-env) checkout next to this repository; every check must pass without errors or warnings.

```sh
docker compose run --rm tools composer --working-dir=/workspace/theme-bench build-manifest
docker compose run --rm tools composer --working-dir=/workspace/theme-bench lint   # PHPStan (max) and PHP-CS-Fixer, also bin/ and timer/
../theme-bench/bin/bench [--skip-build] [--profile]
```
