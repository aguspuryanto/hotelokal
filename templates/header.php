<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $config['site_title']; ?></title>
    <meta name="description" content="<?php echo $config['site_description']; ?>" />
	<meta name="keywords" content="<?php echo $config['site_keywords']; ?>">
	<meta name="author" content="hotelokal.com">
	<meta name="robots" content="index, follow">
	<link rel="canonical" href="<?php echo base_url() . $_SERVER['REQUEST_URI']; ?>/">
    <link href="<?php echo base_url(); ?>/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/dist/style.css" rel="stylesheet">	
	<script type='text/javascript' src="<?php echo base_url(); ?>/dist/js/jquery.min.js"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.css" rel="stylesheet" type="text/css" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.theme.min.css" rel="stylesheet" type="text/css" />
        
    <!--[if lt IE 9]>
        <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
        
    <div class="page-container">  
		<!-- top navbar -->
		<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		   <div class="container">
			<div class="navbar-header">
			   <button type="button" class="navbar-toggle" data-toggle="offcanvas" data-target=".sidebar-nav">
				 <span class="icon-bar"></span>
				 <span class="icon-bar"></span>
				 <span class="icon-bar"></span>
			   </button>
			   <a class="navbar-brand" href="<?php echo base_url(); ?>/">HoteLokal.com</a>
			</div>
		   </div>
		</div>