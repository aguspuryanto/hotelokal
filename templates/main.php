<?php include ('header.php'); ?>
      
		<div id="mega-slider" class="carousel slide hidden-xs" data-ride="carousel">
			<!-- Indicators -->
			<ol class="carousel-indicators">
				<li data-target="#mega-slider" data-slide-to="0" class="active"></li>
				<li data-target="#mega-slider" data-slide-to="1"></li>
				<li data-target="#mega-slider" data-slide-to="2"></li>
			</ol>

			<!-- Wrapper for slides -->
			<div class="carousel-inner" role="listbox">
				<div class="item active beactive">
					<img src="<?php echo base_url(); ?>/dist/img/tauzia.jpg" alt="...">
					<div class="carousel-caption">
						<h2>Welcome to Mega Hotel</h2>
						<p>Cogitavisse erant puerilis utrum efficiantur adhuc expeteretur.</p>
					</div>
				</div>
				<div class="item">
					<img src="<?php echo base_url(); ?>/dist/img/swiss-bel.jpg" alt="...">
					<div class="carousel-caption">
						<h2>Feel Like Your Home</h2>
						<p>Cogitavisse erant puerilis utrum efficiantur adhuc expeteretur.</p>
					</div>
				</div>
				<div class="item">
					<img src="<?php echo base_url(); ?>/dist/img/prasanthi.jpg" alt="...">
					<div class="carousel-caption">
						<h2>Perfect Place for Dining</h2>
						<p>Cogitavisse erant puerilis utrum efficiantur adhuc expeteretur.</p>
					</div>
				</div>
			</div>

			<!-- Controls -->
			<a class="left carousel-control" href="#mega-slider" role="button" data-slide="prev"></a>
			<a class="right carousel-control" href="#mega-slider" role="button" data-slide="next"></a>
		</div>
		
		<div class="search_home">
			<div class="container text-center">
				<h3>Find and book your perfect hotel</h3>
				
				<form method="get" action="<?php echo base_url(); ?>/search">
					<div class="row row-no-padding input-group">
						<div class="col-md-4">
							<label>Nama Lokasi / Hotel Tujuan</label>
							<input type="text" id="city" name="q" class="form-control autocomplete" placeholder="Enter city or hotel name" required>
						</div>
						<div class="col-md-2">
							<label>Check-in</label>
							<input type="text" id="check_in" name="check_in" class="form-control" data-date-format="YYYY-MM-DD" value="<?php echo date("Y-m-d", strtotime("+1 day"));?>" required>
						</div>
						<div class="col-md-2">
							<label>Check-out</label>
							<input type="text" id="check_out" name="check_out" class="form-control" data-date-format="YYYY-MM-DD" value="<?php echo date("Y-m-d", strtotime("+2 day"));?>" readonly>
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
		
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<div class="page-header">
						<h3>Promo Hotel</h3>
					</div>
					
					<?php
					//$promo = Get_Promo();					
					if($promo){
						for ($i=0; $i < 12; $i++) { 
							$newpromo[] = $promo[array_rand($promo)];
						}
					
						foreach (array_slice($newpromo, 0, 12) as $obj) {
							$htl_uri		= base_url() . remove_path( $obj['url'] );
							echo '<div class="col-md-4 portfolio-item thumbnail text-center">
								<a href="'.$htl_uri.'">
									<img src="'.$obj['img'].'">
								</a>
								<h5>'.$obj['title'].'  <span class="label">'.$obj['city'].'</span></h5>							
								<h3><small><s>'.$obj['harga_normal'].'</s> </small> '.$obj['harga'].'</h3>
								<a class="btn btn-warning btn-lg btn-block" href="'.$htl_uri.'"><i class="glyphicon glyphicon-bed"></i> Lihat Kamar</a>
							</div>';
						}
					}
					?>
				</div>
				
				<div class="col-xs-12">
					<div class="page-header">
						<h3>Daftar Hotel di Indonesia</h3>
					</div>
					
					<?php
					$listAll = array('Bali', 'Jakarta', 'Bandung', 'Yogyakarta', 'Surabaya', 'Semarang', 'Kuta', 'Makassar', 'Balikpapan', 'Pekanbaru', 'Medan', 'Batam', 'Bintan', 'Palembang', 'Padang', 'Solo', 'Malang', 'Depok', 'Bogor', 'Tangerang', 'Bekasi');
					?>
					<ul class="list-group list-inline clearfix">
					<?php foreach($listAll as $list){
						echo '<li class="col-xs-4"><a href="'.base_url().'/'.slugify( strtolower($list) ).'">Hotel di '.$list.'</a></li>';
					} ?>
					</ul>
				
					<div class="page-header">
						<h3>Index Hotel di Indonesia</h3>
					</div>
					<ul class="list-inline">
						<li><a href="<?php echo base_url(); ?>/">A</a></li>
						<li><a href="<?php echo base_url(); ?>/">B</a></li>
						<li><a href="<?php echo base_url(); ?>/">C</a></li>
						<li><a href="<?php echo base_url(); ?>/">D</a></li>
						<li><a href="<?php echo base_url(); ?>/">E</a></li>
						<li><a href="<?php echo base_url(); ?>/">F</a></li>
						<li><a href="<?php echo base_url(); ?>/">G</a></li>
						<li><a href="<?php echo base_url(); ?>/">H</a></li>
						<li><a href="<?php echo base_url(); ?>/">I</a></li>
						<li><a href="<?php echo base_url(); ?>/">J</a></li>
						<li><a href="<?php echo base_url(); ?>/">K</a></li>
						<li><a href="<?php echo base_url(); ?>/">L</a></li>
						<li><a href="<?php echo base_url(); ?>/">M</a></li>
						<li><a href="<?php echo base_url(); ?>/">N</a></li>
						<li><a href="<?php echo base_url(); ?>/">O</a></li>
						<li><a href="<?php echo base_url(); ?>/">P</a></li>
						<li><a href="<?php echo base_url(); ?>/">Q</a></li>
						<li><a href="<?php echo base_url(); ?>/">R</a></li>
						<li><a href="<?php echo base_url(); ?>/">S</a></li>
						<li><a href="<?php echo base_url(); ?>/">T</a></li>
						<li><a href="<?php echo base_url(); ?>/">U</a></li>
						<li><a href="<?php echo base_url(); ?>/">V</a></li>
						<li><a href="<?php echo base_url(); ?>/">W</a></li>
						<li><a href="<?php echo base_url(); ?>/">X</a></li>
						<li><a href="<?php echo base_url(); ?>/">Y</a></li>
						<li><a href="<?php echo base_url(); ?>/">Z</a></li>
						<li><a href="<?php echo base_url(); ?>/">Index Berdasarkan Area</a></li>
					</ul>
				</div>
			</div>
		</div><!--/.container-->
		
<?php include ('footer.php'); ?>