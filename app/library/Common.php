<?php
function get_numerics ($str) {
	$str = explode("-", $str);
    return preg_replace("/[^0-9]/","",$str[0]);
}

function get_excerpt($str, $maxLength=100) {
	if(strlen($str) > $maxLength) {
		$excerpt   = substr($str, 0, $maxLength-3);
		$lastSpace = strrpos($excerpt, ' ');
		$excerpt   = substr($excerpt, 0, $lastSpace);
		$excerpt  .= '...';
	} else {
		$excerpt = $str;
	}	
	return $excerpt;
}

/*
	https://www.sitepoint.com/localizing-dates-currency-and-numbers-with-php-intl/

 	$numberFormatter = new NumberFormatter( 'en_US', NumberFormatter::DECIMAL );
 	$numberFormatter = new NumberFormatter( 'en_US', NumberFormatter::SPELLOUT );
 	$numberFormatter = new NumberFormatter( 'en_US', NumberFormatter::CURRENCY );
	var_dump( $numberFormatter->formatCurrency(1234.56789, "USD" ) );
 */
 
function toIDR7($number){
	$numberFormatter = new NumberFormatter( 'id_ID', NumberFormatter::DECIMAL );
	return $numberFormatter->format($number);
}

function thousandsCurrencyFormat($num) {
	$x = round($num);
	$x_number_format = number_format($x);
	$x_array = explode(',', $x_number_format);
	$x_parts = array('k', 'm', 'b', 't');
	$x_count_parts = count($x_array) - 1;
	$x_display = $x;
	$x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
	$x_display .= $x_parts[$x_count_parts - 1];
	return $x_display;
}

/*
• 1 = Satu
• 10 = sepuluh
• 100 = seratus
• 1.000 = seribu
• 10.000 = sepuluh ribu
• 100.000 = seratus ribu
• 1.000.000 = satu juta
• 10.000.000 = sepuluh juta
• 100.000.000 = seratus juta
• 1.000.000.000 = satu milyar
• 10.000.000.000 = sepuluh milyar
• 100.000.000.000 = seratus milyar
• 1.000.000.000.000 = satu triliun
• 10.000.000.000.000 = sepuluh triliun
• 100.000.000.000.000 = seratus triliun
*/
function toBill($number){
	if(!$number) return false;
	
	/*if ($number < 1000000) {
		// satuan juta
		$format = number_format($number) . ' Juta';
	} else if ($number < 1000000000) {
		// Anything less than a billion
		$format = number_format($number / 1000000, 2) . ' Miliar';
	} else {
		// At least a billion
		$format = number_format($number / 1000000000, 2) . ' Triliun';
	}*/
	
	$format = toBill_2($number);
	return $format;
}

function toBill_2($digit) {
    if(!$digit) return false;	
	if ($digit >= 1000000000) {
        return ($digit/ 1000000000). 'M'; //G
    }
    if ($digit >= 1000000) {
        return ($digit/ 1000000).'JT'; //M
    }
    if ($digit >= 1000) {
        return ($digit/ 1000). ' RB'; //K
    }
    return $digit;
}

#    Output easy-to-read numbers
#    by james at bandit.co.nz
function toBill_3($n) {
    // first strip any formatting;
    // $n = (0+str_replace(".","",$n));
        
    // is this a number?
    if(!is_numeric($n)) return false;
        
    // now filter it;
    if($n>=1000000000000) return round(($n/1000000000000),1).' T';
    else if($n>=1000000000) return round(($n/1000000000),1).' M';
    else if($n>=1000000) return round(($n/1000000),1).' Jt';
    else if($n>=1000) return round(($n/1000),1).' Rb';
        
    return number_format($n);
}

function cleanInput($input) {

	$search = array(
		'@<script[^>]*?>.*?</script>@si',   // Strip out javascript
		'@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
		'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
		'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
	);

    $output = preg_replace($search, '', $input);
    return $output;
}

function sanitize($input) {
    if (is_array($input)) {
        foreach($input as $var=>$val) {
            $output[$var] = sanitize($val);
        }
    }
    else {
        if (get_magic_quotes_gpc()) {
            $input = stripslashes($input);
        }
        $input  = cleanInput($input);
        $output = mysql_real_escape_string($input);
    }
    return $output;
}

function current_ip(){	
	if (!empty($_SERVER['HTTP_CLIENT_IP'])){
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	//Is it a proxy address
	}elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}else{
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}