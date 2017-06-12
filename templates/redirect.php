<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HOTELOKAL.COM</title>
    <link href="<?php echo base_url(); ?>/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
	<!--
		html, body{height:100%; margin:0;padding:0}
		
		body {
			padding-top: 60px;
			padding-bottom: 60px;
			line-height: 2.5em;
		}
		
		.container-fluid{
			height:100%;
			display:table;
			width: 100%;
		}

		.form-signin {
			max-width: 50%;
			padding: 15px;
			margin: 0 auto;
		}
		
		.form-signin img {
			padding: 0px 15px 15px;
		}
	-->
	</style>
	
    <script type='text/javascript' src="<?php echo base_url(); ?>/dist/js/jquery.min.js"></script>
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

    <div class="container">
		<div class="row text-center">
			<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
			<!-- hotelokal_728 -->
			<ins class="adsbygoogle"
				 style="display:inline-block;width:728px;height:90px"
				 data-ad-client="ca-pub-7564506822716845"
				 data-ad-slot="6589130889"></ins>
			<script>
			(adsbygoogle = window.adsbygoogle || []).push({});
			</script>
			
			<div class="form-signin">
				<h1>HOTELOKAL.COM</h1>
				<p>Please wait while we transfer you in <b><span id="waktu">5</span> seconds</b></p>
				<div class="">
					<img src="<?php echo base_url(); ?>/dist/img/pegipegi.png" width="200">
				</div>
				<p>Complete the booking process with PEGIPEGI.COM</p>
			</div>
			
			
			<!-- hotelokal_728 -->
			<ins class="adsbygoogle"
				 style="display:inline-block;width:728px;height:90px"
				 data-ad-client="ca-pub-7564506822716845"
				 data-ad-slot="6589130889"></ins>
			<script>
			(adsbygoogle = window.adsbygoogle || []).push({});
			</script>
		</div>
    </div> <!-- /container -->
	
	<script>
	$(document).ready(function(){
		/*window.setInterval(function () {
			var sisawaktu = $("#waktu").html();
			sisawaktu = eval(sisawaktu);
			if (sisawaktu == 0) {
				//location.href = "<?=$htl_uri;?>";
				var url = "<?=$htl_uri;?>";    
				$(location).attr('href',url);
			} else {
				$("#waktu").html(sisawaktu - 1);
			}
		}, 1000);*/
		
		window.setTimeout(function () {
			location.href = "<?=$htl_uri;?>";
		}, 5000);
	});
	</script>
	
</body>
</html>