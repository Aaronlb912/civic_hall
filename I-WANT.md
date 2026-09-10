# I want

I want a usable site a person who is not a developer can edit after install or handoff: the real name in the header, one primary header action if we have one, search under the nav, a thin notice strip if we have notices, a main form, one or two sample forms only where pages already ask for them, and a short after-install note.

Paste that sentence on a new project. Treat it as the product. Do the rest yourself. Do not stop after a first draft. Do not ask for a second prompt for search, empty media, form shortcuts, sample forms, or accessibility.

If the project has its own standing rule or `AGENTS.md`, follow those. The rest of this file is enough if they are not there.

## Constraints

Adapt to whatever the project already is — Drupal CMS recipe, or the stack they named. Do not invent a module or theme if the platform forbids it. Do not commit unless asked.

Work in the folder they named. Live demo from however they already run it. Path repo: whatever they already use.

Use what the platform already ships. Do not invent a second search stack or a project-only module to paper over platform limits.

## Build, then stop

Read the project. Types or routes, menus, home/header/footer, global styles, after-install notes, tests. Keep what they already have. Do not rewrite sample content except links that 404.

Then finish this class of work. Adapt the pieces to the site in front of you. Do not copy another project’s names, paths, or form machine names unless this one already has them.

- Name and title come from the real site-settings (or equivalent), not hardcoded in a second component.
- One primary action in the header if the site has one. That page stays instructions or a counter, not a checkout. Do not add a payment processor.
- Search is a header control. The field drops under the nav and does not cover that action. Use the search the platform already ships, with a real label.
- If the site has alerts or notices, they are a thin strip under the nav. Title + sentence, not color only, not a card.
- Home: one clear lead, one primary action. Empty media stays empty (no gray hole, no broken image). Filled media crops. Type still reads on a photo.
- Contact (or the main form) has hours and shortcuts if that fits. Button says what it does. Required fields have a text note, not only an asterisk.
- One or two sample forms only on pages that already look like they should open a form. Wire the existing links. Do not replace the main contact form.
- Accessibility a person can keep: skip-link focus visible, labeled controls, required-field note, visible focus on nav / primary action / search / fields / buttons, tap targets at least 40px, no horizontal scroll, desktop and ~390px. Do not claim 508 certification. Do not ship a forbidden theme or module to fix platform limits.
- A short after-install note that names the real screens to edit the site name, photos, and labels. Do not claim certification.

Voice matches the organization. Concrete hours, place, phone. No slogans. Sample emails on `.example` if fictional. Leave one slightly boring page.

Verify the paths they already test still work with the strings they test. Then export into the project and the path repo they use.

Stop when the next edit is something they can do in the UI — change the name, a photo, a form label. Do not open a new feature.

If the platform cannot do something without a module or theme it forbids, say so in one sentence and use the closest field that already exists.
