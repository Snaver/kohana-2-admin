<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<!DOCTYPE html>
<html>
  <head>
  	<meta charset="utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Kohana 2 Admin</title>
    <!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
    
    <link href="<?=url::base();?>assets/css/bootstrap.min.css<?=Kohana::config('core.asset_version');?>" rel="stylesheet" media="screen">
    <link href="<?=url::base();?>assets/css/bootstrap-datetimepicker.min.css<?=Kohana::config('core.asset_version');?>" rel="stylesheet" media="screen">
    <link href="<?=url::base();?>assets/css/jQuery-File-Upload/jquery.fileupload-ui.css<?=Kohana::config('core.asset_version');?>" rel="stylesheet" media="screen">
    <link href="<?=url::base();?>assets/css/global.css<?=Kohana::config('core.asset_version');?>" rel="stylesheet">
    
    <script src="<?=url::base();?>assets/js/jquery-1.10.2.min.js<?=Kohana::config('core.asset_version');?>"></script>
    <script src="<?=url::base();?>assets/js/jquery-ui-1.10.3.min.js<?=Kohana::config('core.asset_version');?>"></script>
    
    <script type="text/javascript">
    	// Define some global vars
    	
    	var url_base = '<?=url::base();?>';
    	var admin_url = '<?=Kohana::config('admin.url');?>';
    </script>
    
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="<?=url::base();?>assets/js/html5shiv.js"></script>
      <script src="<?=url::base();?>assets/js/respond.min.js"></script>
    <![endif]-->
    
    <script src="<?=url::base();?>assets/js/tmpl.min.js<?=Kohana::config('core.asset_version');?>"></script>
    <script src="<?=url::base();?>assets/js/functions.js<?=Kohana::config('core.asset_version');?>"></script>
    <script src="<?=url::base();?>assets/js/bootstrap.min.js<?=Kohana::config('core.asset_version');?>"></script>
    <script src="<?=url::base();?>assets/js/bootbox.min.js<?=Kohana::config('core.asset_version');?>"></script>
    <script src="<?=url::base();?>assets/js/moment-with-langs.min.js<?=Kohana::config('core.asset_version');?>"></script>    
    <script src="<?=url::base();?>assets/js/bootstrap-datetimepicker.min.js<?=Kohana::config('core.asset_version');?>"></script>    
    <script src="<?=url::base();?>assets/js/jQuery-File-Upload/jquery.iframe-transport.js<?=Kohana::config('core.asset_version');?>"></script>
    <script src="<?=url::base();?>assets/js/jQuery-File-Upload/jquery.fileupload.js<?=Kohana::config('core.asset_version');?>"></script>    
    <script src="<?=url::base();?>assets/js/jQuery-File-Upload/jquery.fileupload-process.js<?=Kohana::config('core.asset_version');?>"></script>
    <script src="<?=url::base();?>assets/js/jQuery-File-Upload/jquery.fileupload-validate.js<?=Kohana::config('core.asset_version');?>"></script>
    <script src="<?=url::base();?>assets/js/jQuery-File-Upload/jquery.fileupload-ui.js<?=Kohana::config('core.asset_version');?>"></script>
    <script src="<?=url::base();?>assets/js/site.js<?=Kohana::config('core.asset_version');?>"></script>
  </head>
  <body class="">
  	<div class="container">
  		<div class="row">
  			<div class="col-lg-8">
  				<a href="<?=url::base();?>">
  					<img src="<?=url::base();?>assets/images/logo.png" alt="Logo" />		
  				</a>
  			</div>
  			<div class="col-lg-4">
  				<div id="header_account" class="text-right">
  					<?php if(Auth::instance()->logged_in()){ ?>
  						<span><?=Auth::instance()->get_user()->username;?></span> - <a href="<?=url::base();?>account/logout">Logout</a>
  					<?php } else { ?>
  						<a href="<?=url::base();?>account/login">Login</a>
  					<?php } ?>
  				</div>
  				<form id="search_box">
					<div class="input-group">
						<input type="text" class="form-control" placeholder="Search" value="" />
						<span class="input-group-btn">
							<button class="btn btn-default" type="button">Go!</button>
						</span>
					</div>
  				</form>
  			</div>
		</div>
		<noscript>
			<div class="row top-buffer">
	  			<div class="col-lg-6 col-lg-offset-3">
	  				<div class="alert alert-danger">Warning! Javascript is required for this site to run correctly.</div>
	  			</div>
  			</div>
  		</noscript>