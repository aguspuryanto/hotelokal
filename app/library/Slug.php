<?php
function slugify($text,$mode='-'){ 
    $string = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    //return strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $text));
	$slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', $mode, $string)));
	return $slug;
}