<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div class="row">
	<div class="col-lg-6">
		
		<?=new View('includes/fields/input', array('field' => 'example_2_name'));?>
		<?=new View('includes/fields/input', array('field' => 'example_2_email'));?>
		<?=new View('includes/fields/select', array('field' => 'example_2_dropdown'));?>
		<?=new View('includes/fields/select', array('field' => 'example_2_status'));?>
		
	</div>
	<div class="col-lg-6">
		
		<?=new View('includes/fields/file', array('field' => 'example_2_file'));?>
		<?=new View('includes/fields/file', array('field' => 'example_2_file2'));?>
		<?=new View('includes/fields/textarea', array('field' => 'example_2_text'));?>
		
	</div>
</div>