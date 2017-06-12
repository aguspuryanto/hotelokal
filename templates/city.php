<?php
$query		= isset($city) ? $city : "Surabaya";
$rooms 		= isset($_GET['rooms']) ? $_GET['rooms'] : 1;
$guests 	= isset($_GET['guests']) ? $_GET['guests'] : 2;
$page		= isset($_GET['page']) ? $_GET['page'] : 1;
$limit 		= isset($_GET['limit']) ? $_GET['limit'] : 10;
$order 		= isset($_GET['order']) ? $_GET['order'] : 0;

// Optional
$s_area		= isset($_GET['s_area']) ? $_GET['s_area'] : '';
$star		= isset($_GET['star']) ? $_GET['star'] : '';
$min_rate	= isset($_GET['min_rate']) ? $_GET['min_rate'] : '';

$check_in	= isset($_GET['check_in']) ? $_GET['check_in'] : date("Y-m-d", strtotime("+1 day"));
$check_out 	= isset($_GET['check_out']) ? $_GET['check_out'] : date("Y-m-d", strtotime("+2 day"));
if($check_in == $check_out)
	$check_out = date('d-m-Y', strtotime("+1 day", strtotime($check_out)));

$cache_file = "pegipegi_".$query;

if($page > 1){
	$cache_file .= '_p'.$page;
}

if($order > 0){
	$cache_file .= '_o'.$order;
}

if($star){
	$cache_file .= '_s'.$star;
}

if($s_area){
	$get_s_area = pegipegi_Area($s_area);
	$get_s_area = json_decode ( $get_s_area, true );
	//var_dump ($get_s_area);
	
	$s_area		= $get_s_area['s_area'];	
	$cache_file .= '_sa'.$get_s_area['s_area'];
}

if($min_rate){
	$cache_file .= '_mr'.$min_rate;
}

$cache_file = './cache/'.strtolower($cache_file) . '.json';
//echo $cache_file."<br>";

$cache_life = 3600; //1 hour
$filemtime 	= @filemtime($cache_file);

$pegipegi 	= new pegipegiHotel();

if(!file_exists($cache_file) || filesize($cache_file) < 253 || (time() - $filemtime >= $cache_life)){
	$area = pegipegi_Area($query);
	$area = json_decode ( $area, true );
	//var_dump ($area);
	if($page > 1){
		$page = $page * $limit;
	}
	
	$args = array(
		'pref' => $area['htl_pref'],
		'l_area' => $area['l_area'],
		'stay_date' => date("Ymd", strtotime($check_in)),
		'start' => $page
	);
	
	if($order > 0){
		$args = array_merge ($args, array('order' => $order));
	}
	
	if($star){
		if($star > 2) $star_key = 'hotelcs_'.$star; else $star_key = 'hotelcs2ud';
		$args = array_merge ($args, array($star_key => true));
	}
	
	if($s_area){
		$args = array_merge ($args, array('s_area' => $s_area));
	}
	
	if($min_rate){
		$args = array_merge ($args, array('min_rate' => $min_rate, 'max_rate' => ($min_rate+500000)));
	}
	
	//var_dump ($args);	
	$results = $pegipegi->Get_Search($args);
	// konversi ke json
	$json = json_encode($results);
	file_put_contents( $cache_file, $json );
}

//echo $cache_file."<br>";
$str 	= file_get_contents( $cache_file );
$json 	= json_decode($str, true);
//var_dump ($json);

$total_found = $json['NumberOfResults'];
$offset = $json['DisplayPerPage'];
if($offset < 10) $offset = 10;

if(empty($json['Hotel'])):	
		
	if($s_area) $query = 'Area '.$_GET['s_area'];
	$msg = 'Maaf, kami tidak menemukan daftar hotel di '.ucwords($query);
	
	if($json['Message']){
		$msg = $json['Message'];
	}
	
	if(file_exists($cache_file)){
		//unlink($cache_file);
	}
	
	echo '<div class="col-md-12">
		<div class="alert alert-danger text-center">
			<h1>'.$msg.'</h1>
		</div>
	</div>';
else:

	//Hotel Murah di Bandung - 482 hotel Bandung mulai Rp90,000‎
	$config['site_title'] = 'Hotel Murah di '.ucwords($city).' - Hotel Murah Mulai Rp'.toIDR($json['Hotel'][0]['SampleRateFrom']).' - HoteLokal.com';
	
	include ('header.php');
?>
	<div class="search_home">
		<div class="container text-center">
			<h1><b><?php echo ucwords($query);?></b>, saya datang</h1>
			<p>Masukkan tanggal Anda dan temukan lebih dari <?php echo $json['NumberOfResults'];?> akomodasi!</p>
			
			<form class="form-inline" method="get" action="<?php echo base_url(); ?>/search">
			<div class="row row-no-padding">
				<div class="col-md-4">
					<input type="text" id="city" name="q" class="form-control autocomplete" placeholder="Enter city or hotel name" required>
				</div>
				<div class="col-md-2">
					<input type="text" id="check_in" name="check_in" class="form-control" data-date-format="YYYY-MM-DD" value="<?=$check_in;?>" required>
				</div>
				<div class="col-md-2">
					<input type="text" id="check_out" name="check_out" class="form-control" data-date-format="YYYY-MM-DD" value="<?=$check_out;?>" readonly>
				</div>
				<div class="col-md-2">					
					<select class="form-control" name="guests">
						<option value="1">1 Adult</option>
						<option selected="selected" value="2">2 Adult</option>
						<option value="3">3 Adult</option>
						<option value="4">4 Adult</option>
					</select>	
				</div>
				<div class="col-md-2">
					<button type="submit" class="btn btn-warning">Find hotels</button>
				</div>
			</div>
			</form>
		</div>
	</div>
		
	<div class="container">
		<div class="row">
		
			<div id="bookingaff" class="col-md-12 text-center">
				<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
				<!-- hotelokal_728 -->
				<ins class="adsbygoogle"
					 style="display:inline-block;width:728px;height:90px"
					 data-ad-client="ca-pub-7564506822716845"
					 data-ad-slot="6589130889"></ins>
				<script>
				(adsbygoogle = window.adsbygoogle || []).push({});
				</script>
			</div>
			
			<div class="col-md-12">
				<div class="page-header">
					<h3><?=$total_found;?> Hotel Ditemukan di <?php if($s_area) echo 'Area '.ucwords($_GET['s_area']).' - '.ucwords($query); else echo ucwords($query);?></h3>
				</div>
			</div>
			
			<div class="col-md-3">
				<form id="reset_filters" method="get">
					<input type="hidden" name="check_in" value="<?php echo $check_in;?>">
					<input type="hidden" name="check_out" value="<?php echo $check_out;?>">
					<ul class="list-group">
						<li class="list-group-item disabled"><strong>Tarif Kamar</strong></li>
						<?php
						$price_range = array('0','500000','1000000','1500000','2000000');
						foreach($price_range as $range){
							echo '<li class="list-group-item"><input name="min_rate" value="'.$range.'" type="radio"> '.toBill_3($range).' sd '.toBill_3($range+500000).'</li>';
						}
						?>
						<li class="list-group-item disabled"><strong>Bintang Hotel</strong></li>
						<?php
						for($b=1;$b<=5;$b++){
							echo '<li class="list-group-item"><input name="star" value="'.$b.'" type="radio"> '.hotels_stars($b).'</li>';
						}
						?>
						<li class="list-group-item disabled"><strong>Hotel Area</strong></li>
						<?php
						if($json['Hotel']):
							foreach($json['Hotel'] as $hotel){
								$Hotel_Area[] = trim($hotel['Area']['SmallArea']);
							}
							//print_r($Hotel_Area);
							
							$Hotel_Area = array_unique($Hotel_Area);								
							foreach($Hotel_Area as $k => $s_area){
								echo '<li class="list-group-item"><input name="s_area" value="'.$s_area.'" type="radio"> '.$s_area.'</li>';
							}
						endif;
						?>
					</ul>
					<button class="btn btn-warning btn-block btn-lg"><i class="glyphicon glyphicon-search"></i> Ubah Pencarian</button>
				</form>
				
				<a href="http://www.pegipegi.com/hotel/bali/?affid=AFF11295"><img class="img-responsive" src="http://affiliate.pegipegi.com/pegi_assets/img/image_banner_hotel/Bali-300x600.jpg"></a>
			</div>
			
			<div class="col-md-9">
			
				<div class="filter">
					<div class="form-inline">
						<label class="control-label">Urutkan Berdasarkan : </label>
						<select class="form-control sort">
							<option value="popular">Populer</option>
							<option value="2" <?php if($order==2) echo 'selected';?>>Harga Termurah</option>
							<option value="3" <?php if($order==3) echo 'selected';?>>Harga Termahal</option>
						</select>
					</div>
				</div>
				
				<?php if($query=="bali" && $page<=1){ ?>
				<script async src="//www.travelpayouts.com/blissey/scripts_en.js?categories=price%2Ccenter%2Cpopularity&id=30340&type=compact&currency=idr&host=search.hotellook.com&marker=92922.&limit=10" charset="UTF-8"></script>
				
				<?php }else{ ?>
					<table class="table table-hover">
						<tr>
							<td colspan="3"><script async src="//www.travelpayouts.com/chansey/iframe.js?hotel_id=47013900&locale=en&host=hotellook.com%2Fsearch&marker=92922.&currency=idr"></script></td>
						</tr>
						<?php				
						$i=1;
						foreach($json['Hotel'] as $hotel){
							$htl_name		= $hotel['HotelName'];
							//$htl_uri		= $pegipegi->Get_Redirect($hotel['HotelDetailURL']);
							$htl_price		= isset($hotel['SampleRateFrom']) ? $hotel['SampleRateFrom'] : $hotel['PlanSampleRateFrom'];
							$htl_oldprice	= isset($hotel['OriginalSampleRateFrom']) ? $hotel['OriginalSampleRateFrom'] : '';
							$PictureURL		= isset($hotel['PictureURL']) ? $hotel['PictureURL'] : '';
							
							if(is_array($htl_oldprice)) $htl_oldprice = ''; else $htl_oldprice = '<s>Rp'.$htl_oldprice.'</s>';
							
							$htl_uri		= base_url() . $pegipegi->Get_HotelDetail( $hotel['HotelDetailURL'] );
							$htl_uri		.= "/?check_in=".$check_in."&rooms=".$rooms;
							
							echo '<tr>
								<td><img class="img-responsive" src="'.$PictureURL.'" alt="'.$htl_name.'"></td>
								<td class="hinfo">
									<h4 class="media-heading">
										<a href="'.$htl_uri.'" target="_blank"> '.$htl_name.'</a>
										<small class="hotel_star pull-right">'.hotels_stars($hotel['HotelGrade']).'</small>
									</h4>
									<p>'.$hotel['HotelAddress'].'</p>
									<div class="col-bottom text-center hide">
										<div class="btn-group btn-group-justified ">
											<div class="btn-group hidden-xs">
												<button type="button" class="btn btn-default btn-map" id="'.$i.'">Lihat Map</button>
											</div>
											<div class="btn-group">
												<button type="button" class="btn btn-default btn-cekharga" data-toggle="collapse" data-target="#collapse_'.$i.'" id="'.$i.'" data-id="'.$hotel['HotelID'].'" data-term="'.$htl_name.'">Cek Harga</button>
											</div>
										</div>
									</div>
								</td>
								<td width="25%" class="hprice">
									<h5>'.($htl_oldprice).'</h5>
									<h3>Rp'.toIDR($htl_price).'</h3>
									<a class="btn btn-warning btn-lg" href="'.$htl_uri.'" target="_blank">Lihat Kamar</a>
								</td>
							</tr>
								
							<tr id="collapse_'.$i.'" class="collapse">
								<td colspan="3">
									<div class="panel panel-default panel-price">
										<div class="panel-heading"><h4>'.$htl_name.'</h4></div>
										<div class="panel-body">
											
										</div>
									</div>					
								</td>
							</tr>';
											
							$wego_property_type = wego_property_type($hotel['HotelType']);
							$insData = array(
								'wego_id' => $hotel['HotelID'], 'wego_name' => addslashes($htl_name), 'wego_address' => addslashes($hotel['HotelAddress']), 'wego_location' => $hotel['Area']['LargeArea'], 'wego_districts' => $hotel['Area']['SmallArea'], 'wego_latitude' => $hotel['X'], 'wego_longitude' => $hotel['Y'], 'wego_property_type' => $wego_property_type, 'wego_desc' => $hotel['HotelCaption'], 'wego_stars' => $hotel['HotelGrade'], 'wego_total_reviews' => '', 'wego_rooms_count' => '', 'wego_rank' => '', 'wego_satisfaction' => '', 'wego_image' => $PictureURL, 'wego_rate_min' => intval($hotel['SampleRateFrom'])
							);
											
							$columns = implode("`, `", array_keys($insData));
							$values  = implode("', '", array_values($insData));
							$sql_query = "INSERT INTO `hotel_wego` (`$columns`) VALUES ('$values')";
							$db->query($sql_query);
							$i++;
						}
							
						//$connect->close();
						?>
					</table>
				
				<?php } ?>
				
				<?php
				if($total_found>=$offset){ ?>
				<p></p>
				<div class="text-center"><ul class="pagination pagination-centered pagination-lg">
					<?php echo WegoPaging($offset, $total_found);?>
				</ul></div>
				<?php } ?>
			</div>
			
		</div>
	</div>
	
<?php
	include ('popular.php');	
	include ('footer.php');
	
endif;
?>