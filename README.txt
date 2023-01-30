World Time
=============

INTRODUCTION
------------
This module is used to display the current time in a block based on the settings from a configuration form.

INSTALLATION
------------
Install the module using `drush en worldtime` command or from the Extend page in admin panel.

CONFIGURATION
-------------
1. If you go to `admin/config/regional/worldtime-settings` you will see a simple form with three fields.

2. There are default values set in the form and you can update based on the preference.

3. Place the block `World Time Block` in a region from block layout page.

4. Clear the cache before reloading the pages.

IMPORTANT NOTES
---------------
1. The custom block created comes with cache-max-age, cache-tags and cache-context.

2. For the authenticated users, the cache-max-age is being respected and the block time is updated after changes in the form.

3. The same was not the case with anonymous users. Hence, I had to use a cache tag and invalidate it on form save.

4. Next up, the time is not getting updated for anonymous users as the page is getting cached and its respecting the max-age 0 seconds settings. https://www.drupal.org/project/drupal/issues/2352009

5. I have tried putting up the page_cache_kill_switch service to clear the cache on each reload. This works, but clearing the whole page cache for a block did not seem ideal and I have removed that. 
