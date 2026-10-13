# theme-bench

Benchmark of [wp-app-framework](https://github.com/saijsaij/wp-app-framework): what the framework costs per request. It measures a minimal block theme with the framework against the same theme without it, in the release site of [wp-dev-env](https://github.com/saijsaij/wp-dev-env), and writes a Markdown report. Run it before every release of the framework and compare the reports.

## What is measured

The repository holds three parts:

- **theme-bench**, the theme with the framework (namespace `bench`): a text block (`#[PlainText]`, `#[RichText]`), a card (enum and a list of value objects), tabs with tabs inside (`#[InnerBlocks]`, `#[Context]`), the text block with `#[Cache]`, and a REST route.
- **theme-bench-baseline**, the same theme without `functions.php` and thus without the framework: `baseline/style.css` plus the `theme.json` and `templates/` of the theme.
- **bench-timer** (`timer/`), a plugin that measures the phases of a request. For requests with the header `X-Bench: 1` it appends `<!--bench-timing:{…}-->` to the response: time up to `setup_theme` (core), `functions.php` and `after_setup_theme` (theme, the boot of the framework), up to `wp_loaded` (init), the rest of the request (main), the total, memory and database queries. It also registers the REST route `bench-plain/v1/ping` without the framework.

The scenarios run with both themes where it makes sense: an empty page, 100 core blocks (heading and paragraph), the REST route without framework; with the framework theme also 100 text blocks, 100 cards, 25 tabs with 100 tabs, 100 cached text blocks, the REST route of the framework and the render route of the editor with 100 blocks.

## Running it

Requires wp-dev-env and wp-app-framework checked out next to this repository, and `theme-bench` in `WP_THEMES` of wp-dev-env's `.env` (so `scripts/setup` installs its dependencies).

```sh
bin/bench                 # build the ZIPs, install them into the release site, measure, write reports/<time>.md
bin/bench --skip-build    # reuse the ZIPs in dist/
bin/bench --profile       # one Xdebug profile per page and theme instead: reports/<time>-profile.md
```

`bin/bench` builds the release ZIP with the framework checkout (`--package-source`), installs it with `scripts/release-test` (WordPress on tmpfs, OPcache on, Xdebug off, `WP_DEBUG` on, no persistent object cache) and creates the pages with WP-CLI. It measures in rounds (`--rounds`, default 5): each round activates both themes in turn, warms each scenario up (`--warmup`, default 10) and then requests the scenarios in turn (`--runs`, default 40 per scenario), so a drift of the machine affects all of them alike. The requests run inside the container of the site, one at a time. Finally `ab` measures the throughput of the pages (`--requests`, default 300, concurrency 4; 0 skips it).

The report lists median and p95 of each phase, memory and queries, and the differences: the base load of the framework (framework theme against baseline on the same page), framework blocks against core blocks, and the REST route of the framework against the plain one.

With `--profile`, an additional compose file (`data/bench-profile.yml` in wp-dev-env) switches on Xdebug's profiler for requests with `XDEBUG_TRIGGER`. One request per page after five warm-up requests is profiled; `bin/profile-summary.php` shows the time per component (WordPress, framework, Twig, theme) and the functions of the framework and Twig with the largest time. The profiler slows PHP down: compare shares, not times. The next run without `--profile` recreates the site without it.

Two runs differ by up to about 10 % in absolute times on the same machine; the differences between the themes are stable. Compare reports of the same machine only.

## Development

```sh
docker compose run --rm tools composer --working-dir=/workspace/theme-bench build-manifest
docker compose run --rm tools composer --working-dir=/workspace/theme-bench lint   # fix: composer fix
docker run --rm -v "$PWD:/mnt" -w /mnt koalaman/shellcheck:stable bin/bench
```

Run the first two from the wp-dev-env directory, the last from this one.
