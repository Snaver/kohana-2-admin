<?php defined('SYSPATH') OR die('No direct access allowed.');

	$last = array_pop($breadcrumbs);

?>
<ol class="breadcrumb">
	<li><a href="<?=url::base();?>">Home</a></li>
	<?php foreach($breadcrumbs as $link => $breadcrumb){ ?>
		<li><a href="<?=url::base().$link;?>"><?=$breadcrumb;?></a></li>
	<?php } ?>
	<li class="active"><?=$last;?></li>
</ol>