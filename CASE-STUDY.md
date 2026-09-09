# CivicHall notes

Drupal CMS 2 has site templates now. Byte is a product site. Local is for small UK/Ireland councils (services, GDS). Convivial Gov is generic public sector and Morphos is paid for production. LocalGov Drupal is a full UK distribution.

Nothing in that list is a US city hall: meetings, mayor, how-do-I, trash day.

That is what this recipe is.

## Audience

Whoever runs the website for a small city. Or an agency that keeps rebuilding the same content types.

## What is in the repo

Recipe `drupal/civic_hall`. Theme `drupal/civic_hall_theme` (Mercury subtheme).

Types: department, official, meeting, resident_task, news, city_alert.

Paths: `/how-do-i`, `/departments`, `/officials`, `/meetings`, `/news`.

Maple Ridge sample nodes and a main menu.

PHPUnit from the Drupal CMS site template starter kit, plus checks that those listings return 200 and show "Pay a water bill" / "City Clerk".

## Not done

No agenda file field. Not on Drupal.org. Installer not run on the machine that wrote this.

To finish the home page: install with DDEV, lay it out in Canvas, `drush site:export`.
