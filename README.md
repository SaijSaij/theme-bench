# theme-bench

Benchmark of [wp-app-framework](https://github.com/saijsaij/wp-app-framework): what the framework costs per request, where its time goes, and which of its code the scenarios reach. It measures a minimal block theme with the framework against the same theme without it, in the release site of [wp-dev-env](https://github.com/saijsaij/wp-dev-env), and writes a Markdown report. Run it before every release of the framework and compare the reports.

## What is measured

The repository holds four parts:

- **theme-bench**, the theme with the framework (namespace `bench`). Its blocks and services cover the areas of the framework: a text block, a card (enum, list of value objects), tabs (`#[InnerBlocks]`, `#[Context]`), a cached block (`#[Cache]`), panels with two block supports and view scripts, a paragraph stored in the post content with excerpts, a banner reading settings (`#[SettingsGroup]`, Twig global `settings`), a bridge to the plugin (`#[PluginApi]`, public template function), reference blocks (posts, terms, users, a post object), link blocks (a page, a term and a media file by ID, a free URL), an event list (post type, meta, taxonomy, query with meta conditions and sorting, optionally cached), a log table (`#[Table]`, a query of rows with a group, sorting and a limit), hooks, template functions and filters, REST routes (value objects, callback permission, `CacheStore::remember()`), and cron events with an interval in seconds and a start time, which the framework checks on every request, plus a one-time task (`#[CronEvent]`, `#[CronTask]`, `CronScheduler`). A second post type without the block editor has a meta box.
- **theme-bench-baseline**, the same theme without `functions.php` and thus without the framework: `baseline/style.css` plus the `theme.json` and `templates/` of the theme.
- **bench-plugin** (`bench-plugin/`), a plugin with a framework instance of its own: public API, public template function, a hook and a settings page. It is a project of its own; `bin/bench` releases it from a copy with its own git repository, because this repository's `.gitattributes` exclude it from the theme.
- **bench-timer** (`timer/`), a plugin that measures the phases of a request. For requests with the header `X-Bench: 1` it appends `<!--bench-timing:{…}-->` to the response: time up to `setup_theme` (core), `functions.php` and `after_setup_theme` (theme, the boot of the framework), up to `wp_loaded` (init), the rest of the request (main), the total, memory and database queries. With the header `X-Bench-Coverage: 1` it records the code coverage of the framework instead, and so it does for every WP-Cron request (`wp-cron.php`) while Xdebug runs in coverage mode. It also registers the REST route `bench-plain/v1/ping` without the framework. `bin/bench` installs it as `0-bench-timer`, so WordPress loads it before every other plugin.

The scenarios with both themes are an empty page, 100 core blocks (heading and paragraph), a REST route without the framework and the block editor page. With the framework theme come pages with each kind of block, the event query (also cached), the event archive with excerpts, the WordPress functions of Twig, the core post classes and a cached block with variants and tags, the REST routes of the framework (also cached with variants), a REST route that queries 50 rows of a table of the theme, the render and preview routes of the editor, and the admin pages of the framework (settings pages of theme and plugin, the classic edit screen with a meta box). `--coverage` adds paths the timing leaves out, since they change the state other scenarios measure or are no typical requests: the transient backend, saving a post object, a row of a table and settings, a schema sync of the tables (the option `theme_bench_tables` is deleted first), error responses, and a WP-Cron run with a task planned for now and an event made due (`wp cron event delete`: the framework schedules the missing event for now).

## Running it

Requires wp-dev-env and wp-app-framework checked out next to this repository, and `theme-bench` in `WP_THEMES` of wp-dev-env's `.env` (so `scripts/setup` installs its dependencies and links the framework checkout).

```sh
bin/bench                 # build the ZIPs, install them into the release site, measure, write reports/<time>.md
bin/bench --skip-build    # reuse the ZIPs in dist/
bin/bench --dev           # the theme in development mode (source, vendor/, manifest cache) instead of its release ZIP
bin/bench --profile       # one Xdebug profile per page: reports/<time>-profile.md
bin/bench --coverage      # code coverage of the framework over all scenarios: reports/<time>-coverage.md
```

`bin/bench` builds the release ZIPs with the framework checkout (`--package-source`), installs them with `scripts/release-test` (WordPress on tmpfs, OPcache on, Xdebug off, `WP_DEBUG` on, no persistent object cache) and creates the content with WP-CLI. It measures in rounds (`--rounds`, default 5): each round activates both themes in turn, warms each scenario up (`--warmup`, default 10) and then requests the scenarios in turn (`--runs`, default 40 per scenario), so a drift of the machine affects all of them alike. The requests run inside the container of the site, one at a time; admin pages with the cookies of one login, REST routes of the editor with an application password. Finally `ab` measures the throughput of some pages (`--requests`, default 300, concurrency 4; 0 skips it).

The report lists median and p95 of each phase, memory and queries, and the differences: the base load of the framework (framework theme against baseline on the same page), framework blocks against core blocks, queries, and the REST routes and the editor page against their counterparts.

With `--dev`, `bin/bench` builds a copy of the checkout with `build/`, Composer's `vendor/` without development packages and the framework checkout, without `generated/manifest.php`: the framework builds its manifest at run time and keeps it in its development cache, as in development.

With `--profile` or `--coverage`, an additional compose file (`data/bench-xdebug.yml` in wp-dev-env) switches Xdebug to the profiler (for requests with `XDEBUG_TRIGGER`) or to code coverage. The profile run also profiles the block editor page and writes its database queries with their callers (`reports/<time>-queries-<theme>-admin-block-editor.txt`; the timer plugin appends them for requests with `X-Bench-Queries: 1`). `bin/profile-summary.php` shows the time per component (WordPress, framework, Twig, theme) and the functions of the framework and Twig with the largest time; the profiler slows PHP down, so compare shares, not times. `bin/coverage-summary.php` lists per directory of the framework's `src/` the executed lines, the files no request loaded and the functions no request executed. The next run without these options recreates the site without the compose file.

Two runs differ by up to about 10 % in absolute times on the same machine; the differences between the themes are stable. Compare reports of the same machine only. The query count of the block editor page is higher with the framework theme in the timing than in steady state: the framework theme's admin scenarios between two requests make WordPress refill some caches (patterns, update transients) on the next one; the query log of `--profile` shows the steady state.

## Development

```sh
docker compose run --rm tools composer --working-dir=/workspace/theme-bench build-manifest
docker compose run --rm tools composer --working-dir=/workspace/theme-bench lint   # fix: composer fix
docker run --rm -v "$PWD:/mnt" -w /mnt koalaman/shellcheck:stable bin/bench
```

Run the first two from the wp-dev-env directory, the last from this one.
