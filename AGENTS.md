# CivicHall

This is a Drupal CMS site template (a recipe). There is no `web/` directory.

The Mercury subtheme is a sibling package: `drupal/civic_hall_theme` (`civic_hall_theme`). Do theme work there, not in this recipe.

## Files

- `recipe.yml`
- `composer.json` (`type: drupal-recipe`)
- `config/`
- `content/` (Maple Ridge sample nodes)
- `tests/`

## Do not

Add `*.info.yml` in the recipe. Recipes cannot ship a module or theme. The theme’s `.info.yml` lives in the sibling theme package.

Pin exact versions. Use `^`.

Add `cweagans/composer-patches` or `extra.patches`.

Prefix the package with `drupal_cms_`.

Commit `vendor/`, `.env`, or `settings.local.php`.

## Types

resident_task, department, official, meeting, news, city_alert

## Install

DDEV, Drupal CMS, path repo to this folder, pick CivicHall. After you build pages in Canvas: `ddev drush site:export`.

## Process

Standing build process is in `.cursor/rules/civic-hall-process.mdc`. Follow it on every “I want …” request. Do not wait for a second spec.
