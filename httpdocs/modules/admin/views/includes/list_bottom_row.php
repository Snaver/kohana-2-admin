<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

<div class="row">
	<div class="col-lg-3 col-md-3 col-sm-3">
		<?php
			print form::dropdown(
				array(
					'id'		=> 'list_actions',
					'name'		=> 'actions',
					'class'		=> 'form-control',
					'disabled'	=> 'disabled'
				),
				$actions,
				array()
			);
		?>
	</div>
	<div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3 text-right"><?=$pagination->render();?></div>
</div>