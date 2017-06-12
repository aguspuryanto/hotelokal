
		<footer id="footer">
			<div class="container">
				<div class="row">
					<div class="col-md-6">
						<ul class="list-inline">
							<li><a href="<?php echo base_url(); ?>/">Home</a></li>
							<li><a href="#">About</a></li>
							<li><a href="#">Rooms</a></li>
							<li><a href="#">Blog</a></li>
							<li><a href="#">Contact</a></li>
						</ul>
					</div>
					<div class="col-md-6">
						<p class="copyright pull-right">&copy; 2015 <a href="<?php echo base_url(); ?>/">HoteLokal</a>. All rights reserved.</p>
					</div>
				</div>
			</div>
		</footer>
		
	</div><!--/.page-container-->
	
    <script type='text/javascript' src="<?php echo base_url(); ?>/dist/js/bootstrap.min.js"></script>
	<script type='text/javascript' src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>
	<?php if($area_arr): ?><script type="text/javascript">
		var area_arr = <?php echo $area_arr;?>;
	</script><?endif; ?>
	
	<script>
	$(function() {
		$( "#check_in" ).datepicker({
			dateFormat:'yy-mm-dd',
			minDate:0,
			onSelect: function(dateText, inst) {
				var actualDate = new Date(dateText);
				var newDate = new Date(actualDate.getFullYear(), actualDate.getMonth(), actualDate.getDate()+1);
				$('#check_out').datepicker('option', 'minDate', newDate );
			}
		});
		
		$( "#check_out" ).datepicker({      
			disabled:true,
			dateFormat:'yy-mm-dd',
			minDate: $("input#check_in").val(),
			onSelect: function( selectedDate ) {
				$( "input#check_in" ).datepicker( "option", "maxDate", selectedDate );
			}
		});
		
		$("select.sort").change(function () {
			var pattern = new RegExp('(\\?|\\&)(order=).*?(&|$)')
			var uri = window.location.search.replace(pattern,'');
			
			if (uri.indexOf('?') === -1) {
				uri += '?order=' + this.value;
			}else{
				uri += '&order=' + this.value;
			}
			window.location = uri;
		});
		
		/*var cache = {};
		$(".autocomplete").autocomplete({
            minLength: 3,
			source: function( request, response ) {
				var term = request.term;
				if ( term in cache ) {
					response( cache[ term ] );
					return;
				}

				$.getJSON("<?php echo base_url(); ?>/api/area", request, function( data, status, xhr ) {
					cache[ term ] = data;
					response( data );
				});
			}
        });*/
		
		$('.autocomplete').autocomplete({
			source: area_arr,
			minLength: 0,
			focus: function( event, ui ) {
				$(this).val(ui.item.label);
				return false;  
			},
			select: function(event, ui) {
				$(this).val(ui.item.label);
				return false; // Prevent the widget from inserting the value.
			}
		});
		
		$('.counter .plus').click(function(){
			if( !$(this).parent().parent().find('input').val() ){
				value = 1;
				$(this).parent().parent().find('input').val(value);
			}
			else {
				value++;
				$(this).parent().parent().find('input').val(value);
			}
		});

		$('.counter .minus').click(function(){
			if( $(this).parent().parent().find('input').val() == 1 || $(this).parent().parent().find('input').val() == '' ){
				value = '';
				$(this).parent().parent().find('input').val(value);
			}
			else {
				value--;
				$(this).parent().parent().find('input').val(value);
			}
		});
		
		var heights = $("#single .thumbnail").map(function() {
			return $(this).height();
		}).get(),

		maxHeight = Math.max.apply(null, heights);
		$("#single .thumbnail").height(maxHeight);

	});
	</script>

</body>
</html>