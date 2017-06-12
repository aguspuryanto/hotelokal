<?php
/*
	PEGIPEGI
	@author : Agus Puryanto
	@email	: aguspuryanto@gmail.com
*/

function toIDR($number){
	return number_format($number,0,',','.');
}

function toIDR_3($number){
	$var = number_format($number,0,',','.');
	return number_format($var, 3);
}

function wego_property_type($str='Hotel'){
	$array = array(1 => 'Hotel', 2 => 'Hostel', 3 => 'Bed and Breakfast', 4 => 'Serviced Apartment', 5 => 'Resort', 6 => 'Villa', 7 => 'Motel');
	
	return array_search($str, $array);
}

function hotels_stars($id=1){
	$star = '';
	for($i=1;$i<=$id;$i++){
		$star .= '<span class="glyphicon glyphicon-star"></span>';
	}
	return $star;
}

function cleanText($str){
	$str = explode(", ", $str);
	$str = trim($str[0]);
	$str = str_replace("-", " ", $str);
	return $str;
}

function pegipegi_Filter($str){
	$word = array('Bandar ');
	
	$newstr = str_replace($word, '', $str);
	return $newstr;
}

function pegipegi_Area($area){
	global $db;
	
	$area = ( cleanText($area) );
	$sql_query = "SELECT * FROM hotel_area_pegipegi WHERE htl_name LIKE '%{$area}' AND l_area != '0' ORDER BY l_area ASC LIMIT 1";
	//echo $sql_query."<br>";
	
	$result = $db->query($sql_query);
	while ($row = $result->fetch_assoc()) {
		$data = $row;
	}
	return json_encode($data);
}

if(!function_exists('geturl')){
	function getUrl() {
		$pageURL = (strtolower($_SERVER["HTTPS"]) == "on") ? "https://" : "http://";
		if ($_SERVER["SERVER_PORT"] != "80")    {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}

		return $pageURL;
	}
}

/* wego_paging
 * Last Update : 11/06/2016
 * - unset page
 */
 
function wego_paging($page_id){
	$pageURL = getUrl();
	
	$parsed = parse_url($pageURL);
	$query = $parsed['query'];
	parse_str($query, $params);
	unset($params['page']);
	
	if(count($params)>0){
		$pageURL = $parsed['path'] . '?' . http_build_query($params);
	}else{
		$pageURL = $parsed['path'] . http_build_query($params);
	}

	if (strpos($pageURL, '?')){
		$pageURL .= '&page='.$page_id;
	}else{
		$pageURL .= '?page='.$page_id;
	}
	
	return $pageURL;
}

function WegoPaging($per_page=10, $max_page=100) {
	
	/* Set current, prev and next page */
	$page = (!isset($_GET['page']))? 1 : $_GET['page']; 
	$prev = ($page - 1);
	$next = ($page + 1);

	/* Calculate the offset */
	if($per_page < 10) $per_page = 10;
	$jumPage = floor($max_page / $per_page);
	//echo $jumPage."<br>";
	
	$html   = "";	
	// menampilkan link previous
	if ($page > 1) $html .= '<li><a href="'.wego_paging($prev).'">&laquo;</a></li>';

	// memunculkan nomor halaman dan linknya
	for($i = 1; $i <= $jumPage; $i++)
	{
		if ((($i >= $page - 2) && ($i <= $page + 5)) || ($i == 1) || ($i == $jumPage)){
			//if (($i == 1))  $html .= '<li class="active"><a href="'.wego_paging($i).'">'.$i.'</a></li>';
			//if (($showPage != ($jumPage - 1)) && ($i == $jumPage))  $html .= '...';		
			if (($i == $page) && ($i > 1)) $html .= '<li class="active"><a href="'.wego_paging($i).'">'.$i.'</a></li>';			
			else $html .= '<li><a href="'.wego_paging($i).'">'.$i.'</a></li>';
			//if ($i > 1) $html .= '<span class="active"><a href="'.$newUrl.$i.'">'.$i.'</a></span>';
			//$showPage = $i;
		}
	}

	// menampilkan link next
	if ($page < $jumPage) $html .= '<li><a href="'.wego_paging($next).'">&raquo;</a></li>';
	
	return $html;
}

function _get_pegipegi($id, $cache_life = 300){
	global $pegipegi;
	
	$args = array(
		'h_id' => $id
	);
	
	$cache_file	= _cache('pegipegi_'.$id);
	//print_r ($cache_file).' \t';

	$filemtime 	= @filemtime($cache_file);		
	if (!$filemtime || (time() - $filemtime >= $cache_life)){
		ob_start();
		$results 	= $pegipegi->Get_Search($args);
		// konversi ke json
		$json 		= json_encode($results);
		file_put_contents( $cache_file, $json );
	}
	return $cache_file;
}

function _travelpayouts(){
	return array(
		'25034' => 'Yogyakarta',
		'30340' => 'Bali'
	);
}

function Get_GalleryHotel($url){
	//http://www.pegipegi.com/hotel/surabaya/hotel_sahid_surabaya_924800/
	
	//print_r ($url);
	$url .= '?affid=AFF11295';
	
	$hotel_id = explode("_", $url);
	$hotel_id = end($hotel_id);
	$hotel_id = get_numerics($hotel_id);
	
	$cache_file = APP_PATH . "/cache/gallery_".$hotel_id;
	$cache_file .= ".html";
	
	if (!file_exists($cache_file)) {	
		$html = file_get_contents($url, true, stream_context_create(array('http' => array('ignore_errors' => true))));
		file_put_contents( $cache_file, $html, LOCK_EX);
	}
	
	$html = @file_get_html($cache_file);
	if($html){
		//widget promoHotel
		foreach($html->find('#fullGallery .mainSlider .item') as $e){
			echo $e->find("a")->href;
		}
	}
}

function Get_UpdatePromo($cache_life = 300){
	
	$url = "http://www.pegipegi.com";	
	$cache_file = "cache/pegipegi_hotel_promo.html";
	
	$filemtime 	= @filemtime($cache_file);
	if (!file_exists($cache_file) || (time() - $filemtime >= $cache_life)) {	
		$html = file_get_contents($url, true, stream_context_create(array('http' => array('ignore_errors' => true))));
		file_put_contents( $cache_file, $html, LOCK_EX);
	}
	
	$html = @file_get_html($cache_file);
	if($html){
		//widget promoHotel
		foreach($html->find('.promoHotel .tab-content .hotelSlider') as $e){
			$data['city'] = $e->id;		
			foreach($e->find("ul>li") as $el){
				$data['url'] = str_replace("//", "", $el->find('.hoverBox a', 0)->href);
				$data['title']	= $el->find('.title h3', 0)->plaintext;
				$data['harga_normal']	= $el->find('.normalPrice', 0)->plaintext;
				$data['harga']	= $el->find('.diskonPrice', 0)->plaintext;
				$data['diskon']	= $el->find('.balonDiskon', 0)->plaintext;
				$data['img']	= $el->find('.top img', 0)->src;
				
				if($data['title']){
					$results[] = $data;
				}
			}
		}
		
		if($results){
			//PHP 5.3
			usort($results, function($a, $b) {
				return $a['harga'] - $b['harga'];
			});
			
			$json = json_encode($results);
			print_r ($json);
			file_put_contents( 'cache/promo.json', $json, LOCK_EX);
		}
	}
}

function Get_Promo(){	
	$cache_file	= 'cache/promo.json';
	if(file_exists($cache_file)){
		$str 	= file_get_contents( $cache_file );
		$json 	= json_decode($str, true);
		return $json;
	}
}

function get_pegiarea(){
	$file_cache = 'cache/AreaPegiPegi.xml';	
	if(!file_exists($file_cache)){		
		$html = file_get_contents('http://jws.pegipegi.com/rs/rsp0200/Rst0201Action.do?key=peg11187712b79', true, stream_context_create(array('http' => array('ignore_errors' => true))));
		file_put_contents( $file_cache, $html, LOCK_EX);
		
	}else{
		libxml_use_internal_errors(true);
		$xml = simplexml_load_file( $file_cache );
		if ($xml === false) {
			echo "Failed loading XML\n";
		}else{
			$json = array();
			foreach($xml->Area->Region->Prefecture as $Prefecture){
				foreach($Prefecture->LargeArea as $LargeArea){
					$json[] = array("label" => (string)$LargeArea['name'], "value" => (string)$LargeArea['cd']);
				}
			}
			return json_encode($json);
		}
		
	}
}
	
function Get_Area(){
	global $db;
	
	$results = $db->query("select * from hotel_area_pegipegi WHERE l_area !='0' AND s_area='0' ORDER BY htl_name ASC");	
	$json = array();
	while ($row = $results->fetch_assoc()) {
		$json[] = $row['htl_name'];
	}
	
	return ($json);	
}

function remove_path($url){
	$url = preg_replace("%(https?://)?(www\.)?pegipegi\.com/hotel?%im", "", $url);
	$url = str_replace("http:", "", $url); //<a href="http://hotelokal.comhttp:/solo/the_edelweiss_hideaway_solo_971120">
	$url = rtrim($url,"/");
	return ($url);
}

function get_related($hotel_id, $city=""){
	global $db;
	
	// RELATED
	$sql_query = "SELECT * FROM hotel_wego WHERE wego_id !='".$hotel_id."'";
	if($city) $sql_query .= " AND wego_location LIKE '%".$city."'";
	$sql_query .= " AND wego_image LIKE '%pegipegi%' LIMIT 5";
	//print_r ($sql_query);
	
	$related = null;
	$results = $db->query($sql_query);	
	if($results->num_rows > 0){
		while ($row = $results->fetch_assoc()) {
			$related[] = $row;
		}
		return $related;
	}
}

//select a.*, b.wego_name from hotel_tracking a LEFT JOIN hotel_pegipegi b ON (b.wego_id=a.yadNo) order by counter DESC;
function get_related_populer($limit=6){
	global $db;
	
	$results = $db->query("SELECT a.*,b.wego_name,b.wego_location,b.wego_image FROM hotel_tracking a LEFT JOIN hotel_wego b ON (b.wego_id=a.yadNo) ORDER BY counter DESC LIMIT $limit");	
	if($results->num_rows > 0){
		while ($row = $results->fetch_assoc()) {
			$related[] = $row;
		}
		
		return $related;
	}
}