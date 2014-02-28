<?php defined('SYSPATH') OR die('No direct access allowed.');

	if(isset($label)){
		$label = $label;
	} elseif(array_key_exists('label', $fields[$field])){
		$label = $fields[$field]['label'];
	} else {
		$label = '';
	}

	if(isset($required)){
		$required = $required;
	} elseif(array_key_exists('required', $fields[$field])){
		$required = $fields[$field]['required'];
	} else {
		$required = false;
	}
	
	$error = array_key_exists($field,$errors);

?>
<div data-field="<?=$field;?>" class="form-group field_input clearfix <?=($error ? 'has-error' : '');?>">
	<label for="<?=$field;?>" class="col-lg-3 col-md-9 control-label"><?=$label;?> <?php if($required){ ?><span class="required">*</span><?php } ?></label>
    <div class="col-lg-9 col-md-9">
    	<div class="checkbox">
    		<label>
		    <?php
				print form::checkbox(
					array(
						'id'			=> $field,
						'name'			=> $field,
						'class'			=> '',
						'data-required'	=> $required ? 1 : 0,
						'data-title'	=> $error ? 'Errors' : '',
						'data-content'	=> $error ? $errors[$field] : ''
					),
					1,
					array_key_exists($field,$fields) ? ($fields[$field]['value'] ? true : false) : false
				);
			?>
			Yes
			</label>
		</div>
	</div>
</div>