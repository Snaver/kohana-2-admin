<?php defined('SYSPATH') OR die('No direct access allowed.');

	$last = array_pop($breadcrumbs);

?>
<ol class="breadcrumb">
	<li><a href="<?=url::base().Kohana::config('admin.url');?>">Home</a></li>
	<?php foreach($breadcrumbs as $link => $breadcrumb){ ?>
		<li><a href="<?=url::base().Kohana::config('admin.url');?>/<?=$link;?>"><?=$breadcrumb;?></a></li>
	<?php } ?>
	<li class="active"><?=$last;?></li>
</ol>