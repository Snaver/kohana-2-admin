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
	<label for="<?=$field;?>" class="col-lg-3 control-label"><?=$label;?> <?php if($required){ ?><span class="required">*</span><?php } ?></label>
    <div class="col-lg-9">
    	<?php foreach($fields[$field]['options'] as $value => $label){ ?>
	    	<div class="radio radio-inline">
	    		<label>
				    <?php
						print form::radio(
							array(
								'id'			=> $field,
								'name'			=> $field,
								'class'			=> '',
							),
							$value,
							array_key_exists($field,$fields) ? ($fields[$field]['value'] == $value ? true : false) : false
						);
						echo $label;
					?>
				</label>
		    </div>
	    <?php } ?>
	</div>
</div>