<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div class="row top-buffer">
	<ul class="col-lg-2 col-md-2 col-sm-2 nav nav-pills nav-stacked">
		<li class="<?=(URI::segment(1) == '' || URI::segment(1) == 'dashboard' ? 'active' : '');?>"><a href="<?=url::base().Kohana::config('admin.url');?>">Dashboard</a></li>
		<li class="<?=(URI::segment(1) == 'example_1' ? 'active' : '');?>"><a href="<?=url::base().Kohana::config('admin.url');?>/example_1">Example 1</a></li>
		<li class="<?=(URI::segment(1) == 'example_2' ? 'active' : '');?>"><a href="<?=url::base().Kohana::config('admin.url');?>/example_2">Example 2</a></li>
		<li class="<?=(URI::segment(1) == 'example_3' ? 'active' : '');?>"><a href="<?=url::base().Kohana::config('admin.url');?>/example_3">Example 3</a></li>
		<?php if(Auth::instance()->logged_in('admin')){ ?>
			<li class="<?=(URI::segment(1) == 'users' ? 'active' : '');?>"><a href="<?=url::base().Kohana::config('admin.url');?>/users">Users</a></li>
		<?php } ?>
	</ul>
	<div id="content" class="col-lg-10 col-md-10 col-sm-10">