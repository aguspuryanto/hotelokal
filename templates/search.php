<?php
$query		= isset($q) ? $q : "Surabaya";

$check_in	= isset($_GET['check_in']) ? $_GET['check_in'] : date("Y-m-d", strtotime("+1 day"));
$check_out 	= isset($_GET['check_out']) ? $_GET['check_out'] : date("Y-m-d", strtotime("+2 day"));
if($check_in == $check_out){
	$check_out = date('d-m-Y', strtotime("+1 day", strtotime($check_out)));
}

$cache_file = './cache/pegipegi_' . slugify(strtolower($query),"_") . '.json';
$cache_life = 3600; //1 hour
$filemtime 	= @filemtime($cache_file);

$pegipegi 	= new pegipegiHotel();
if(!file_exists($cache_file) || filesize($cache_file) < 253 || (time() - $filemtime >= $cache_life)){
	
	$area = pegipegi_Area($query);
	$area = json_decode ( $area, true );
	//var_dump ($area);

	$args = array(
		'h_name' => $q,
		'stay_date' => date("Ymd", strtotime($check_in)),
		'start' => $page
	);
	
	if($area){
		$args = array_merge($args, array(
			'pref' => $area['htl_pref'],
			'l_area' => $area['l_area']
		));
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
	
	echo '<div class="col-md-12">
		<div class="alert alert-danger text-center">
			<h1>'.$msg.'</h1>
		</div>
	</div>';
	
else:

	$config['site_title'] = 'Pencarian Hotel '.ucwords($query).' Ditemukan - HoteLokal.com';
	$config['site_description'] = ''.ucwords($query).' Hotel - HoteLokal.com';
	
	include ('header.php');
?>
	<div class="search_home">
		<div class="container text-center">
			<h1><b><?php echo ucwords($query);?></b>, saya datang</h1>
			<p>Masukkan tanggal Anda dan temukan lebih dari <?php echo $json['NumberOfResults'];?> akomodasi!</p>
			
			<form class="form-inline" method="get" action="<?php echo base_url(); ?>/search">
			<div class="row row-no-padding input-group">
				<div class="col-md-4">
					<label>Nama Lokasi / Hotel Tujuan</label>
					<input type="text" id="city" name="q" class="form-control autocomplete" placeholder="<?=ucwords($query);?>" required>
				</div>
				<div class="col-md-2">
					<label>Check-in</label>
					<input type="text" id="check_in" name="check_in" class="form-control" data-date-format="YYYY-MM-DD" value="<?=$check_in;?>" required>
				</div>
				<div class="col-md-2">
					<label>Check-out</label>
					<input type="text" id="check_out" name="check_out" class="form-control" data-date-format="YYYY-MM-DD" value="<?=$check_out;?>" readonly>
				</div>
				<div class="col-md-2">
					<label>Informasi Tamu:</label>
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
				<ins class="bookingaff" data-aid="953192" data-target_aid="953187" data-prod="banner" data-width="728" data-height="90" data-banner_id="23156">
					<!-- Anything inside will go away once widget is loaded. -->
					<a href="//www.booking.com?aid=953187">Booking.com</a>
				</ins>
				<script type="text/javascript">
					(function(d, sc, u) {
					  var s = d.createElement(sc), p = d.getElementsByTagName(sc)[0];
					  s.type = 'text/javascript';
					  s.async = true;
					  s.src = u + '?v=' + (+new Date());
					  p.parentNode.insertBefore(s,p);
					  })(document, 'script', '//aff.bstatic.com/static/affiliate_base/js/flexiproduct.js');
				</script>
			</div>
			
			<div class="col-md-12">
				<div class="page-header" style="margin-bottom:0px;">
					<h3><?=$total_found;?> Hotel <?php echo ucwords($query);?> Ditemukan</h3>
				</div>
			</div>
			
			<div class="col-md-12">
			
				<table class="table table-hover">
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
						</tr>';
						$i++;
					}
					?>
				</table>
					
			</div>
			
		</div>
	</div>
	
<?php
	include ('footer.php');
	
endif;
?>