<?php
/*
 * 	Slim Framework: 1.6.7
 * 	Requirement: PHP < 5.3
 * 	Documentation: https://github.com/codeguy/Slim/tree/1.6.7/docs
 * 	//For PHP < 5.3
	$app->get('/books/:id', 'show_book');
	function show_book($id) {
		$app = Slim::getInstance();
		$app->render('myTemplate.php', array('id' => $id));
	}

 * 	Connecting Slim Framework and MySQL
 *	http://scottnelle.com/616/connecting-slim-framework-mysql/
 */

date_default_timezone_set ('Asia/Jakarta');
error_reporting( error_reporting() & ~E_NOTICE );

require 'config.php';
require 'app/My_Pegipegi.php';

// Instantiation
require 'Slim/Slim.php';
$app = new Slim(array(
    'mode' => 'development',
	'debug' => true
));

$app->config(array(
	'templates.path' => APP_PATH . '/templates',
    'log.enable' => true
));

$app->hook('slim.before.dispatch', function() use ($app, $db) {
	//Get the Request resource URI
	$resourceUri = $app->request()->getResourceUri();	
	$resourceUri = str_replace("/","",$resourceUri);
	
	//if(!in_array($app->router()->getCurrentRoute(), 'search')) {
	if($resourceUri=='search') {		
		$params = $app->request()->get();
		$query = isset($params['q']) ? $params['q'] : '';
		
		$db->query("INSERT INTO hotel_terms VALUES ('null','".addslashes($query)."','1','".date('Y-m-d H:m:s')."') ON DUPLICATE KEY UPDATE terms_count=terms_count+1");
	}	
});

$app->get('/', function () use ($app,$config) {
	$listAll 	= Get_Area();
	$area_arr 	= get_pegiarea();
	$promo 	= Get_Promo();
	
	$app = Slim::getInstance();
    $app->render('main.php', array(
		'listAll' => $listAll,
		'area_arr' => $area_arr,
		'promo' => $promo,
		'config' => $config
	));
});

$app->get('/redirect(/?)', function () use ($app, $db, $config) {
	$args = $app->request()->get();
	//var_dump ($args);
	
	$pegipegi 	= new pegipegiHotel();
	
	$columns = implode("`, `", array_keys($args));
	$values  = implode("', '", array_values($args));
	$sql_query = "INSERT INTO `hotel_tracking` (`$columns`) VALUES ('$values')";
	$sql_query .= " ON DUPLICATE KEY UPDATE counter = counter+1";
	
	// Simpan Ke database
	$db->query($sql_query);
	
	/*
	 *https://www.pegipegi.com/uo/uop5200/uow5207.do?pricePerNight=162149&request_locale=in_ID&roomCapa=2&stayDay=19&stayCount=1&yadNo=905845&roomTypeCd=0132527&STATUS=0&stayMonth=4&groupList=0358&roomCount=1&planCd=00497928&stayYear=2017&roomCrack=100000&afCd=PGI&TEMP1=LEVEL_R&alreadyAddedParamUrl=Y
	 *
	 */
	
	/*$args = array(
		'pricePerNight' => $args['pricePerNight'],
		'request_locale' => 'in_ID',
		'roomCapa' => '2',
		'stayDay' => date('d', strtotime($args['check_in'])),
		'stayCount' => 1,
		'yadNo' => $hotelid,
		'roomTypeCd' => $args['RoomCD'],
		'STATUS' => 0,
		'stayMonth' => date('n', strtotime($args['check_in'])),
		'groupList' => 0358,
		'roomCount' => 1,
		'planCd' => $args['PlanCD'],
		'stayYear' => date('Y', strtotime($args['check_in'])),
		'roomCrack' => 100000,
		'afCd' => 'PGI',
		'TEMP1' => 'LEVEL_R',
		'alreadyAddedParamUrl' => 'Y'
	);
	
	$htl_uri = "https://www.pegipegi.com/uo/uop5200/uow5207.do";
	if($args){
		$htl_uri .= "?".http_build_query($args);
	}
	$htl_uri = $pegipegi->Get_RedirectUri($htl_uri);
	//print_r ($htl_uri);*/
	
	$cache_file = './cache/pegipegi_'.$args['HotelID'] . '.json';
	$str 	= file_get_contents( $cache_file );
	$json 	= json_decode($str, true);
	
	if(!$json['NumberOfResults']) {
		$app->render('404.php', array('title' => 'Mohon maaf, saat ini sistem sedang mengalami gangguan'));		
	}
	
	$htl_uri = $json['Hotel']['HotelDetailURL'] . '?affid=' . $pegipegi->affid();
	$app->render('redirect.php', array( 'htl_uri' => $htl_uri ));
});

$app->get('/search(/?)', function () use ($app, $db) {
	$app = Slim::getInstance();
	
	$allGetParams = $app->request()->get();
	$city = isset($allGetParams['q']) ? $allGetParams['q'] : '';
	
    $app->render('city.php', array(
		'city' => $city,
		'db' => $db,
		'config' => $config
	));
});

$app->get('/api/updatepromo', function () use ($app, $db) {
	$cache_file = Get_UpdatePromo();
});

$app->get('/api/area', function () use ($app, $db) {
	
	$args = $app->request()->get();
	$term = $args['term'];
	
	$sql = "select * from hotel_area_pegipegi WHERE l_area !='0' AND s_area='0'";
	if($term) $sql .= " AND htl_name LIKE '%".$term."%'";
	$sql .= " ORDER BY htl_name ASC";
	//print_r ($sql);
	
	$results = $db->query($sql);	
	$json = array();
	while ($row = $results->fetch_assoc()) {
		//$json['label'] = $row['htl_name'];
		$json[] = array("label" => $row['htl_name']);
	}
	
	print_r (json_encode($json));	
});

$app->get('/:city(/)', function ($city) use ($app, $db, $config) {
	$app = Slim::getInstance();	
	$city = isset($city) ? $city : '';
	
	$app->render('city.php', array(
		'city' => $city,
		'db' => $db,
		'config' => $config
	));
});

$app->get('/:city/:post_id(/?)', function ($city,$post_id) use ($app, $db, $config) {
	$app = Slim::getInstance();	
	
	$args = $app->request()->get();
	
	$city 	 = isset($city) ? $city : '';
	$post_id = explode("_", $post_id);
	$post_id = end($post_id);
	//print_r ($post_id);
	
	// SETTING
	$htl_id 	= intval($post_id);

	/*$check_in	= isset($args['check_in']) ? $args['check_in'] : date("Y-m-d", strtotime("+1 day"));
	$check_out 	= isset($args['check_out']) ? $args['check_out'] : date("Y-m-d", strtotime("+2 day"));
	if($check_in == $check_out){
		$check_out = date('d-m-Y', strtotime("+1 day", strtotime($check_out)));
	}*/

	$cache_file = './cache/pegipegi_'.$htl_id . '.json';
	$cache_life = 3600; //1 hour
	$filemtime 	= @filemtime($cache_file);

	$pegipegi 	= new pegipegiHotel();
	if(!file_exists($cache_file) || filesize($cache_file) < 253 || (time() - $filemtime >= $cache_life)){
		ob_start();
		$args = array( 'h_id' => $htl_id );
		$results = $pegipegi->Get_Search($args);
		// konversi ke json
		$json = json_encode($results);
		file_put_contents( $cache_file, $json );
	}
	
	$str 	= file_get_contents( $cache_file );
	$json 	= json_decode($str, true);
	//var_dump ($json);
	if(!$json['NumberOfResults']) {
		//echo '<h1>Hotel tidak di temukan</h1>'; return false;
		$app->render('404.php', array('title' => 'Mohon maaf, saat ini sistem sedang mengalami gangguan'));
		
	}else{
		
		$url = $json['Hotel']['HotelDetailURL'].'/?affid=AFF11295';
		$cache_file = explode("/", $url);
		$cache_file = './cache/'.$cache_file[5].'.html';

		if(!file_exists($cache_file) || filesize($cache_file) < 62){
			$context = stream_context_create(array("http" => array("header" => "User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36")));

			$html = @file_get_contents($url, false, $context);
			$html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
			file_put_contents($cache_file, $html);
		}

		$html = str_get_html(file_get_contents($cache_file));
		if($html){
			foreach($html->find('div.item') as $element) :
				$img1[] = $element->find('a.fancybox',0)->attr['href'];
			endforeach;
		}
					
		$app->render('single.php', array(
			'gallery' => $img1,
			'json' => $json,
			'config' => $config
		));
	}
	
});

$app->run();
