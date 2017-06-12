<?php
// SETTING TITLE & DESCRIPTION
// Best Western Papilio Hotel Surabaya - Booking Murah Mulai 496,364
$config['site_title'] = $json['Hotel']['HotelName'].' - HoteLokal.com';

//$config['site_description'] = 'HoteLokal.com: Pesan Hotel '.$json['Hotel']['HotelName'].' Termurah Mulai '.toIDR($json['Hotel']['SampleRateFrom']);
$config['site_description'] = $json['Hotel']['HotelCaption'];

$config['site_keywords'] = $json['Hotel']['HotelName'].', '.$json['Hotel']['HotelName'].' voucher, voucher '.$json['Hotel']['HotelName'].', '.$json['Hotel']['HotelName'].' harga promo, diskon '.$json['Hotel']['HotelName'];

$check_in	= isset($_GET['check_in']) ? $_GET['check_in'] : date("Y-m-d", strtotime("+1 day"));
$check_out 	= isset($_GET['check_out']) ? $_GET['check_out'] : date("Y-m-d", strtotime("+2 day"));
if($check_in == $check_out) $check_out = date('d-m-Y', strtotime("+1 day", strtotime($check_out)));
	
$total_found = $json['NumberOfResults'];
$offset = $json['DisplayPerPage'];
if($offset < 10) $offset = 10;

include ('header.php');

if(empty($json['Hotel'])):	
		
	if($s_area) $query = 'Area '.$_GET['s_area'];
	$msg = 'Maaf, kami tidak menemukan daftar hotel di '.ucwords($query);
	
	if($json['Message']){
		$msg = $json['Message'];
	}
	
	if(file_exists($cache_file)){
		//unlink($cache_file);
	}
	
	echo '
	<div class="col-md-12">
		<div class="alert alert-danger text-center">
			<h1>'.$msg.'</h1>
		</div>
	</div>
	';
else:
	
?>
	<div class="search_home" <?php if($json['Hotel']['Area']['LargeArea']=="Bandung") echo 'style="background-image:url(http://www.airyrooms.com/assets/5ca56e7e2e75f07f49af5d33364d9089.jpg);background-repeat:no-repeat;background-size:cover;background-position:center center;height:350px;z-index:0;padding-top:220px;"'; ?>>
		<div class="container text-center">
			<h3>Find and book your perfect hotel</h3>
			<form method="get" action="<?php echo base_url(); ?>/search">
					<div class="row row-no-padding input-group">
						<div class="col-md-4">
							<label>Nama Lokasi / Hotel Tujuan</label>
							<input type="text" id="city" name="q" class="form-control autocomplete" placeholder="<?=$json['Hotel']['Area']['LargeArea'];?>" required>
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
							<div class="input-group counter hide">
                                <input type="text" class="form-control" placeholder="1">
                                <span class="input-group-btn">
                                    <button class="btn btn-default minus" type="button"><i class="glyphicon glyphicon-minus"></i></button>
                                </span>
                                <span class="input-group-btn">
                                    <button class="btn btn-default plus" type="button"><i class="glyphicon glyphicon-plus"></i></button>
                                </span>
                            </div><!-- /input-group -->

						</div>						
						<div class="col-md-2" style="padding-top:25px;">
							<button type="submit" class="btn btn-warning">Cari Hotel</button>
						</div>
					</div>
				</form>
		</div>
	</div>
	
	<div id="single" class="container single">
		<div class="row">
			
			<div class="col-md-12">
				
				<ol class="breadcrumb">
					<li><a href="<?php echo base_url(); ?>/">Hotel</a></li>
					<?php
					if($json['Hotel']['Area']) :
						//echo '<li> '.$json['Hotel']['Area']['Prefecture'].' </li>';
						echo '<li> <a href="'.base_url().'/'.strtolower($json['Hotel']['Area']['LargeArea']).'">'.$json['Hotel']['Area']['LargeArea'].'</a></li>';
						echo '<li> '.$json['Hotel']['Area']['SmallArea'].' </li>';
					endif;
					?>
				</ol>
				
				<h2><?php echo $json['Hotel']['HotelName'];?> <small class="hotel_star"><?php echo hotels_stars($json['Hotel']['HotelGrade']);?></small></h2>
				<p><?php echo $json['Hotel']['HotelAddress'];?></p>
				
			</div>
			
			<div class="col-md-8 col-thin-left">
					
				<div class="hotel_detail_img thumbnail">
					<?php /*if($htl_id==902192){ ?>
					<div class="video-container"><iframe width="100%" height="360" src="https://www.youtube.com/embed/fNzdeFkG1I8?autoplay=1" frameborder="0" allowfullscreen></iframe></div>
					<?php }else{ ?>
					<img src="<?php echo $json['Hotel']['PictureURL'];?>">
					<?php }*/ ?>					
				  
					<!-- main slider carousel -->
					<div class="row">
						<div class="col-md-12" id="slider">
							
								<div id="carousel-bounding-box">
									<div id="myCarousel" class="carousel slide">
										<!-- main slider carousel items -->
										<div class="carousel-inner">
											<?php
											$j=0;
											foreach($gallery as $img){
											echo '<div class="item" data-slide-number="'.$j.'">
											  <img src="'.$img.'" class="img-responsive">
											</div>';
												$j++;
											} ?>
										</div>
										<!-- main slider carousel nav controls --><!-- Controls -->
										<a class="left carousel-control" href="#myCarousel" data-slide="prev">
											<span class="icon-prev"></span>
										</a>
										<a class="right carousel-control" href="#myCarousel" data-slide="next">
											<span class="icon-next"></span>
										</a>
									</div>
								</div>

						</div>
						
						<!-- thumb navigation carousel -->
						<div class="col-md-12 hidden-sm hidden-xs" id="slider-thumbs" style="padding:20px;">						
							<!-- thumb navigation carousel items -->
							<ul class="list-inline text-center">
								<?php
								$j=0;
								foreach($gallery as $img){
									echo '<li><a id="carousel-selector-'.$j.'">
									<img src="'.$img.'" width="80px" height="60px">
									</a></li>';
									$j++;
								} ?>
							</ul>						
						</div>
					</div>
					<!--/main slider carousel-->
					
					<script>
					$(function() {
						$('#myCarousel .item:first').addClass('active');
						$('#slider-thumbs ul>li>a:first').addClass('selected');
						
						$('#myCarousel').carousel({
							interval: 4000
						});

						// handles the carousel thumbnails
						$('[id^=carousel-selector-]').click( function(){
						  var id_selector = $(this).attr("id");
						  var id = id_selector.substr(id_selector.length -1);
						  id = parseInt(id);
						  $('#myCarousel').carousel(id);
						  $('[id^=carousel-selector-]').removeClass('selected');
						  $(this).addClass('selected');
						});

						// when the carousel slides, auto update
						$('#myCarousel').on('slid', function (e) {
						  var id = $('.item.active').data('slide-number');
						  id = parseInt(id);
						  $('[id^=carousel-selector-]').removeClass('selected');
						  $('[id=carousel-selector-'+id+']').addClass('selected');
						});
					});
					</script>
					<style>
						.selected img {
							opacity:0.5;
						}
						ul.list-inline>li>a img { width:80px!important;height:60px!important;}
					</style>
				</div>
					
				<!--<div class="panel panel-default">
					<div class="panel-body">-->
					
						<blockquote>
							<?php echo trim($json['Hotel']['HotelCaption']);?>
						</blockquote>
						<p>In partnership with <img src="<?=base_url();?>/dist/img/pegipegi.png" width="100"></p>
						
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Tipe kamar</th>
									<th>Tarif per kamar</th>
									<th></th>
								</tr>
							</thead>
							<tbody>									
							<?php
							$plan = $json['Hotel']['Plan'];
							if(is_array($plan)){								
								if(count($plan) ==22 ){
									$PlanName		= str_replace("?", " ", $plan['PlanName']);
									$RoomName		= str_replace("?", " ", $plan['RoomName']);
									$OriginalRate 	= $plan['OriginalPlanSampleRateFrom'];
									$Rate 			= toIDR($plan['PlanSampleRateFrom']);
									//$htl_uri 		= $pegipegi->Get_RedirectUri($plan['PlanDetailURL']);
									//$htl_uri 		= $pegipegi->Get_Redirect($json['Hotel']['HotelID'], $room);
									
									$args = array(
										'pricePerNight' => $plan['PlanSampleRateFrom'],
										'roomCapa' => 2,
										'yadNo' => $json['Hotel']['HotelID'],
										'roomTypeCd' => $plan['RoomCD'],
										'planCd' => $plan['PlanCD'],
										'check_in' => $check_in,
										'HotelID' => $json['Hotel']['HotelID']
									);										
									$htl_uri 		= base_url().'/redirect/?'.http_build_query($args);
										
									// Custom
									if($plan['BrkfstFlg']==1) $RoomName .= "- With Breakfast";
									if($OriginalRate) $OriginalRate = '<s>Rp'.toIDR($OriginalRate).'</s>'; else $OriginalRate = '<br>';
									
									echo '<tr>
										<td>
											<b>'.strip_tags($RoomName).'</b>
											<p class="help-block">'.strip_tags($PlanName).'</p>
										</td>
										<td width="25%" class="hprice">
											<b>Rp'.$Rate.'</b>										
										</td>
										<td><a class="btn btn-warning btn-block" href="'.$htl_uri.'" rel="nofollow" target="_blank">Reservasi</a></td>
									</tr>';
								
								}else{
									foreach($plan as $room){
										$PlanName		= str_replace("?", " ", $room['PlanName']);
										$RoomName		= str_replace("?", " ", $room['RoomName']);
										$OriginalRate 	= $room['OriginalPlanSampleRateFrom'];
										$Rate 			= toIDR($room['PlanSampleRateFrom']);
										//$htl_uri 		= $pegipegi->Get_RedirectUri($room['PlanDetailURL']);
										//$htl_uri 		= $pegipegi->Get_Redirect($json['Hotel']['HotelID'], $room);
										
										/*https://www.pegipegi.com/uo/uop5200/uow5207.do?pricePerNight=162149&request_locale=in_ID&roomCapa=2&stayDay=19&stayCount=1&yadNo=905845&roomTypeCd=0132527&STATUS=0&stayMonth=4&groupList=0358&roomCount=1&planCd=00497928&stayYear=2017&roomCrack=100000&afCd=PGI&TEMP1=LEVEL_R&alreadyAddedParamUrl=Y
										*/
										
										$args = array(
											'pricePerNight' => $plan['PlanSampleRateFrom'],
											'roomCapa' => 2,
											'yadNo' => $json['Hotel']['HotelID'],
											'roomTypeCd' => $plan['RoomCD'],
											'planCd' => $plan['PlanCD'],
											'check_in' => $check_in,
											'HotelID' => $json['Hotel']['HotelID']
										);										
										$htl_uri 		= base_url().'/redirect/?'.http_build_query($args);
										
										// Custom
										if($room['BrkfstFlg']==1) $RoomName .= "- With Breakfast";
										if($OriginalRate) $OriginalRate = '<s>Rp'.toIDR($OriginalRate).'</s>'; else $OriginalRate = '<br>';
										
										echo '<tr>
											<td>
												<b>'.strip_tags($RoomName).'</b>
												<p class="help-block">'.strip_tags($PlanName).'</p>
											</td>
											<td width="25%" class="hprice">
												<b>Rp'.$Rate.'</b>										
											</td>
											<td><a class="btn btn-warning btn-block" href="'.$htl_uri.'" rel="nofollow" target="_blank">Reservasi</a></td>
										</tr>';	
									}
								}
								
							}else{
								$PlanName		= str_replace("?", " ", $plan['PlanName']);
								$RoomName		= str_replace("?", " ", $plan['RoomName']);
								$OriginalRate 	= $plan['OriginalPlanSampleRateFrom'];
								$Rate 			= toIDR($plan['PlanSampleRateFrom']);
								//$htl_uri 		= $pegipegi->Get_RedirectUri($plan['PlanDetailURL']);
								//$htl_uri 		= $pegipegi->Get_Redirect($json['Hotel']['HotelID'], $room);
								
								$args = array(
									'pricePerNight' => $plan['PlanSampleRateFrom'],
									'roomCapa' => 2,
									'yadNo' => $json['Hotel']['HotelID'],
									'roomTypeCd' => $plan['RoomCD'],
									'planCd' => $plan['PlanCD'],
									'check_in' => $check_in,
									'HotelID' => $json['Hotel']['HotelID']
								);										
								$htl_uri 		= base_url().'/redirect/?'.http_build_query($args);
								
								// Custom
								if($plan['BrkfstFlg']==1) $RoomName .= "- With Breakfast";
								if($OriginalRate) $OriginalRate = '<s>Rp'.toIDR($OriginalRate).'</s>'; else $OriginalRate = '<br>';
								
								echo '<tr>
									<td>
										<b>'.strip_tags($RoomName).'</b>
										<p class="help-block">'.strip_tags($PlanName).'</p>
									</td>
									<td width="25%" class="hprice">
										<b>Rp'.$Rate.'</b>										
									</td>
									<td><a class="btn btn-warning btn-block" href="'.$htl_uri.'" rel="nofollow" target="_blank">Reservasi</a></td>
								</tr>';
							}
							?>
							</tbody>
						</table>
						
						<small class="hide">*Tarif kamar belum termasuk: Pajak hotel 10%, Biaya layanan 11% </small>
						
						<blockquote class="hide"><i class="glyphicon glyphicon-scissors"></i> <strong> Biaya Pembatalan</strong> <span class="pull-right">Rp 50,000</span></blockquote>
						
					<!--</div>
				</div>-->
				
				<div class="row">					
					<div id="populer" class="col-md-12">
						
						<div class="page-header">
							<h4>Hotel Populer</h4>
						</div>
					
						<?php
						$populer = get_related_populer(6);
						if($populer):
							foreach($populer as $obj){
								$htl_uri = '#'; // base_url() . remove_path( $obj['url'] );
								echo '<div class="col-md-4 thumbnail text-center">
									<a href="'.$htl_uri.'">
										<img class="img-responsive" src="'.$obj['wego_image'].'">
									</a>
									<h5>'.$obj['wego_name'].'  <span class="label">'.$obj['city'].'</span></h5>							
									<h3><small><s>'.$obj['harga_normal'].'</s> </small> '.$obj['harga'].'</h3>
									<a class="btn btn-warning btn-lg btn-block" href="'.$htl_uri.'"><i class="glyphicon glyphicon-bed"></i> Lihat Kamar</a>
								</div>';
							}
						endif;
						?>
					</div>
				</div>
				
			</div>
				
			<div class="col-md-4 col-thin-right">
				<div class="panel panel-default panel-related">
					<div class="panel-heading">Rekomendasi Hotel Lainnya:</div>
					<div class="panel-body">
						<ul class="media-list">
						<?php
						$related = get_related($htl_id, $json['Hotel']['Area']['LargeArea']);
						if($related):
						foreach($related as $htl){
							//print_r ($htl);
							$htluri = base_url().'/'.strtolower($htl['wego_location']).'/'.slugify($htl['wego_name'],'_').'_'.$htl['wego_id'];
							
							echo '<li class="media clearfix">
								<div class="media-left pull-left">									
									<a href="'.$htluri.'"><img src="'.$htl['wego_image'].'" width="100"></a>
								</div>
								<div class="media-body">
									<a href="'.$htluri.'"><h4 class="media-heading">'.$htl['wego_name'].'</h4></a>
									<a rel="nofollow" class="btn btn-warning btn-block" href="'.$htluri.'">Rp. '.toIDR($htl['wego_rate_min']).'</a>
								</div>
							</li>';
						}
						endif;
						?>
						</ul>
					</div>
				</div>
				
				<div class="panel panel-default">
					<div class="panel-heading">Peta & Petunjuk Arah</div>
					<div class="panel-body" style="padding:0px;margin-bottom:0px;">
						<iframe width='100%' height='350' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='https://maps.google.com/maps?&amp;q=<?=urldecode($json['Hotel']['HotelAddress']);?>&amp;output=embed'></iframe>
					</div>
				</div>
				
				<div class="panel">
					<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
					<!-- hotelokal_300 -->
					<ins class="adsbygoogle"
						 style="display:inline-block;width:300px;height:600px"
						 data-ad-client="ca-pub-7564506822716845"
						 data-ad-slot="3635664486"></ins>
					<script>
					(adsbygoogle = window.adsbygoogle || []).push({});
					</script>
				</div>
				
			</div>
			
		</div><!-- /row -->
	</div>
	
	<?php include ('popular.php'); ?>
	
<?php	
endif;

include ('footer.php');
?>