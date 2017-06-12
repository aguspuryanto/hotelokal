<?php
$populer = array("Bali", "Jakarta", "Bandung", "Jogja", "Surabaya", "Makassar", "Semarang", "Malang", "Padang", "Medan", "Solo", "Lombok", "Pangandaran", "Manado", "Bogor", "Batam", "Balikpapan", "Banjarmasin", "Tretes", "Mataram", "Palembang", "Pekanbaru", "Sukabumi", "Tangerang", "Tasikmalaya", "Garut");
?>
		<div class="container populer">
			<div class="row">
				<div class="col-md-12">
					<div class="page-header">
						<h3>Daftar Hotel Populer</h3>
					</div>
					
					<ul class="list-group list-inline clearfix">
					<?php
					foreach($populer as $list){
						echo '<li class="col-xs-4"><a href="'.base_url().'/'.slugify( strtolower($list) ).'">Hotel di '.$list.'</a></li>';
					}
					?>
					</ul>
				</div>
			</div>
		</div>