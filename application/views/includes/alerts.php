<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<?php if($this->session->get('alert')){ $alert = $this->session->get('alert'); ?>
	<div class="row">
		<div class="col-lg-12">
			<div class="alert alert-<?=$alert['type'];?>"><?=$alert['message'];?><a class="close" data-dismiss="alert" href="#" aria-hidden="true" title="Dismiss">&times;</a></div>
		</div>
	</div>
<?php $this->session->delete('alert'); } ?>