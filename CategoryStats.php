<?php
/*
 *
 * ========================= CategoryStats.php =================================
 * Revision Information
 *   Changed: $LastChangedDate$
 *   Revision: $LastChangedRevision$
 *   Last Update By: $Author$
 */
 
/*       1         2         3         4         5         6         7         8
12345678901234567890123456798012345678901234567890123456789012346579801234567890
*/

# Not a valid entry point, skip unless MEDIAWIKI is defined
if (!defined('MEDIAWIKI')) {
        echo <<<EOT
To install my extension, put the following line in LocalSettings.php:
require_once( "\$IP/extensions/CategoryStats/CategoryStats.php" );
EOT;
        exit( 1 );
}

$wgExtensionCredits['specialpage'][] = array(
	'name' => 'Category Statistics',
	'version' => '1.0',
	'author' => 'Dan Rinkes - Texas Instruments, Inc.',
	'url' => 'http://www.mediawiki.org/wiki/Extension:CategoryStats',
	'descriptionmsg' => 'categorystats-desc',
);

$dir = dirname(__FILE__) . '/';
$wgAutoloadClasses['CategoryStats'] = $dir . 'CategoryStats_body.php';
$wgSpecialPages['CategoryStats'] = 'CategoryStats';
$wgExtensionMessagesFiles['CategoryStats'] = $dir . 'CategoryStats.i18n.php';
$wgExtensionAliasesFiles['CategoryStats'] = $dir . 'CategoryStats.alias.php';
