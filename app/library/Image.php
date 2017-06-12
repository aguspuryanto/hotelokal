<?php
/*
 * cache/2145428.jpg, ../dist/cache/4548177.jpg
 */
	 
function get_image($file){
	$filename = explode('/', $file);
	$filename = end($filename);
	
	return DIR_CACHE . '/' .$filename;
}