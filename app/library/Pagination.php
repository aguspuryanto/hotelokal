<?php

function Paging($per_page=10, $max_page=100) {
	
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
	if ($page > 1) $html .= '<li><a href="'.currentUrl($prev).'">&laquo;</a></li>';

	// memunculkan nomor halaman dan linknya
	for($i = 1; $i <= $jumPage; $i++)
	{
		if ((($i >= $page - 2) && ($i <= $page + 5)) || ($i == 1) || ($i == $jumPage)){
			//if (($i == 1))  $html .= '<li class="active"><a href="'.currentUrl($i).'">'.$i.'</a></li>';
			//if (($showPage != ($jumPage - 1)) && ($i == $jumPage))  $html .= '...';		
			if (($i == $page) && ($i > 1)) $html .= '<li class="active"><a href="'.currentUrl($i).'">'.$i.'</a></li>';			
			else $html .= '<li><a href="'.currentUrl($i).'">'.$i.'</a></li>';
			//if ($i > 1) $html .= '<span class="active"><a href="'.$newUrl.$i.'">'.$i.'</a></span>';
			//$showPage = $i;
		}
	}

	// menampilkan link next
	if ($page < $jumPage) $html .= '<li><a href="'.currentUrl($next).'">&raquo;</a></li>';
	
	return $html;
}

function currentUrl($page_id){
	global $config;
	
	$pageURL = getUrl();
	$pageURL = parse_url ($pageURL);

	$query = array();
	parse_str($pageURL['query'], $query);
	unset($query['page']);

	$path = isset($pageURL['path']) ? $pageURL['path'] : '';
	$query = !empty($query) ? '?'. http_build_query($query) : '';

	$pageURL = $pageURL['scheme']. '://'. $pageURL['host']. $path. $query;
	
	if (strpos($pageURL, '?page=')){
		$pageURL .= '&page='.$page_id;
	}else{
		$pageURL .= '?page='.$page_id;
	}
	
	return $pageURL;
}