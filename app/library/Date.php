<?php

function the_date($date, $format='Y-m-d H:i:s'){
	$new_date = date($format, strtotime($date));
	return $new_date;
}