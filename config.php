<?php
/* define a working directory
 * dirname(__FILE__) => D:\AppServ\www\hotelokal PHP < 5.3 PHP < 5.3
 * __DIR__ only exists with PHP >= 5.3
 */
 
define('APP_PATH', dirname(__FILE__));

$config['site_url'] = 'http://localhost:8080/nginep'; // no slash(/) at end

$config['site_title'] = 'Situs Booking Hotel Online - Dapatkan tawaran hotel terbaik‎';
$config['site_description'] = 'Cari Hotel Termurah? Cari hotel di HoteLokal.com';
$config['site_keywords'] = 'Hotel Lokal, Hotel Lokal di Bandung, Hotel Lokal di Jakarta, Hotel Lokal di Yogya, Hotel Lokal di Surabaya, Hotel Lokal di Bogor, Hotel Lokal di Semarang, Hotel Lokal di Solo, Hotel Lokal di Malang, Hotel Lokal di Medan, Hotel murah di Bandung, Hotel murah di Jakarta, Hotel murah di Yogya, Hotel murah di Surabaya, Hotel murah di Bogor, Hotel murah di Semarang, Hotel murah di Solo, Hotel murah di Malang, Hotel murah di Medan';

$config['hostname'] = 'localhost';
$config['username'] = 'root';
$config['password'] = '103Wonokromo';
$config['database'] = 'hotelokal';

define('DIR_APP', 'app');
define('DIR_ASSETS', 'dist');
define('DIR_CACHE', DIR_ASSETS.'/cache');
define('DIR_DB', DIR_APP.'/database');
define('DIR_THEMES', DIR_APP.'/templates');

// LOAD DATABASE
require DIR_DB .'/mysqli.php';

/*
	LOAD LIBRARY :	
	require DIR_APP .'/library/Url.php';
	require DIR_APP .'/library/Pagination.php';
	require DIR_APP .'/library/Slug.php';
	require DIR_APP .'/library/Common.php';
	require DIR_APP .'/library/Post.php';
	require DIR_APP .'/library/Date.php';
	require DIR_APP .'/library/Image.php';
*/

foreach (glob(DIR_APP .'/library/*.php') as $file_name){
    require $file_name;
}

/*
	LOAD HELPER :
	require DIR_APP .'/helper/simple_html_dom.php';
*/

foreach (glob(DIR_APP .'/helper/*.php') as $file_name){
    require $file_name;
}

/*
	LOAD PLUGIN :
*/
foreach (glob(DIR_APP .'/plugin/pegipegi/*.php') as $file_name){
    require $file_name;
}