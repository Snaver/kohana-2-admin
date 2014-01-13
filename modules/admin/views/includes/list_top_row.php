<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

<div class="row">
	<div class="col-lg-2">
		<a href="<?=url::base().Kohana::config('admin.url');?>/<?=$section_url;?>/add" class="btn btn-primary">New</a>
	</div>
	<div id="admin_list_options" class="col-lg-10 text-right">
		<div class="form-group" style="margin-right:15px">
			<label for="filters" class="control-label">Filter</label>
			<?php
				print form::dropdown(
					array(
						'id'		=> 'filter',
						'name'		=> 'filter',
						'class'		=> 'form-control'
					),
					$filters,
					$this->input->get('filter') ? $this->input->get('filter') : '',
					'style="width:150px"'
				);
			?>
		</div>
		<div class="form-group">
			<label for="inputEmail1" class="control-label">Limit</label>
			<?php
				print form::dropdown(
					array(
						'id'		=> 'per_page',
						'name'		=> 'per_page',
						'class'		=> 'form-control'
					),
					$per_page,
					$this->input->get('per_page') ? $this->input->get('per_page') : 50
				);
			?>
		</div>
	</div>
</div>