readme.txt

MODULE:
This extension adds a special script page that will report the number of hits
on each page in a specified category.  This breaks down results found in the 
CategoryHits extension into specifi pages within the category.
INSTALLATION:
 - The follwing files should be added to the extensions\CategoryStats
 directory under the wiki install directory.
   - CategoryStats.alias.php
   - CategoryStats.i18n.php
   - CategoryStats.php
   - CategoryStats_body.php

 - The following line needs to be added to LocalSettings.php

 require_once("$IP/extensions/CategoryStats/CategoryStats.php");


USE:
 - The script will appear on the "Special Pages" page as "CategoryStats"
 - The script can be linked to at the following location:
 	{MediaWiki Root Location}/index.php/Special:CategoryStats