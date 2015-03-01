<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<!DOCTYPE html>
<html>
  <head>
  	<meta charset="utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Kohana 2 Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <?=assets::less(
      'assets/styles/bootstrap/bootstrap.less',
      array(
        'body-bg'=>'231f20',
        'text-color'=>'#fff',
        'legend-color'=>'#fff',
        'nav-tabs-active-link-hover-bg'=>'#fff'
      )
    );?>
    <link href="<?=url::base();?>assets/styles/bootstrap-datetimepicker.min.css<?=Kohana::config('core.asset_version');?>" rel="stylesheet" media="screen">
    <link href="<?=url::base();?>assets/styles/jQuery-File-Upload/jquery.fileupload-ui.css<?=Kohana::config('core.asset_version');?>" rel="stylesheet" media="screen">
    <link href="<?=url::base();?>assets/styles/global.css<?=Kohana::config('core.asset_version');?>" rel="stylesheet">
    
    <script src="<?=url::base();?>assets/scripts/jquery-1.10.2.min.js<?=Kohana::config('core.asset_version');?>"></script>
    <script src="<?=url::base();?>assets/scripts/jquery-ui-1.10.3.min.js<?=Kohana::config('core.asset_version');?>"></script>
    
    <script type="text/javascript">
    	// Define some global vars
    	
    	var url_base = '<?=url::base();?>';
    	var admin_url = '<?=Kohana::config('admin.url');?>';
    </script>
    
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="<?=url::base();?>assets/scripts/html5shiv.js"></script>
      <script src="<?=url::base();?>assets/scripts/respond.min.js"></script>
    <![endif]-->
    
    <script src="<?=url::base();?>assets/scripts/tmpl.min.js<?=Kohana::config('core.asset_version');?>"></script>
    <script src="<?=url::base();?>assets/scripts/functions.js<?=Kohana::config('core.asset_version');?>"></script>
    <script src="<?=url::base();?>assets/scripts/bootstrap.min.js<?=Kohana::config('core.asset_version');?>"></script>
    <script src="<?=url::base();?>assets/scripts/bootbox.min.js<?=Kohana::config('core.asset_version');?>"></script>
    <script src="<?=url::base();?>assets/scripts/moment-with-langs.min.js<?=Kohana::config('core.asset_version');?>"></script>    
    <script src="<?=url::base();?>assets/scripts/bootstrap-datetimepicker.min.js<?=Kohana::config('core.asset_version');?>"></script>    
    <script src="<?=url::base();?>assets/scripts/jQuery-File-Upload/jquery.iframe-transport.js<?=Kohana::config('core.asset_version');?>"></script>
    <script src="<?=url::base();?>assets/scripts/jQuery-File-Upload/jquery.fileupload.js<?=Kohana::config('core.asset_version');?>"></script>    
    <script src="<?=url::base();?>assets/scripts/jQuery-File-Upload/jquery.fileupload-process.js<?=Kohana::config('core.asset_version');?>"></script>
    <script src="<?=url::base();?>assets/scripts/jQuery-File-Upload/jquery.fileupload-validate.js<?=Kohana::config('core.asset_version');?>"></script>
    <script src="<?=url::base();?>assets/scripts/jQuery-File-Upload/jquery.fileupload-ui.js<?=Kohana::config('core.asset_version');?>"></script>
    <script src="<?=url::base();?>assets/scripts/site.js<?=Kohana::config('core.asset_version');?>"></script>
  </head>
  <body class="">
  	<div class="container">
  		<div class="row">
  			<div class="col-lg-8 col-md-8 col-sm-8">
  				<a href="<?=url::base();?>">
  					<img src="<?=url::base();?>assets/images/logo.png" alt="Logo" />		
  				</a>
  			</div>
  			<div class="col-lg-4 col-md-4 col-sm-4">
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
	  			<div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">
	  				<div class="alert alert-danger">Warning! Javascript is required for this site to run correctly.</div>
	  			</div>
  			</div>
  		</noscript>