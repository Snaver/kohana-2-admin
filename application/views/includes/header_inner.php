<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div class="row top-buffer">
	<ul class="col-lg-2 nav nav-pills nav-stacked">
		<li class="<?=(URI::segment(1) == '' || URI::segment(1) == 'dashboard' ? 'active' : '');?>"><a href="<?=url::base();?>">Dashboard</a></li>
		<li class="<?=(URI::segment(1) == '' || URI::segment(1) == 'example_1' ? 'active' : '');?>"><a href="<?=url::base();?>example_1">Example 1</a></li>
		<?php if(Auth::instance()->logged_in('admin')){ ?>
			<li class="<?=(URI::segment(1) == 'users' ? 'active' : '');?>"><a href="<?=url::base();?>users">Users</a></li>
		<?php } ?>
	</ul>
	<div id="content" class="col-lg-10">