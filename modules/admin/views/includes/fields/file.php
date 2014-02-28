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
	
	if(array_key_exists($field,$fields) && $fields[$field]['value'] && is_array($fields[$field]['value'])) {
		$files = htmlentities(json_encode(array('files' => array(arr::remove_prefix($fields[$field]['value'],'file_')))));
	} else {
		$files = false;
	}

	$error = array_key_exists($field,$errors);
	
?>
<div data-field="<?=$field;?>" class="form-group field_input clearfix <?=($error ? 'has-error' : '');?>">
	<label for="<?=$field;?>" class="col-lg-3 col-md-3 control-label"><?=$label;?> <?php if($required){ ?><span class="required">*</span><?php } ?></label>
	<div class="col-lg-9 col-md-9">
		<div class="row single_uploader" date-area="<?=$section_url;?>" data-field="<?=$field;?>" data-files="<?=$files;?>">
			<div class="col-lg-5 col-md-5 col-sm-5">
				<span class="btn btn-primary fileinput-button">
					<i class="glyphicon glyphicon-plus"></i>
					<span>Upload file</span>
					<input type="file" name="files[]" />
				</span>
			</div>
			<div class="files col-lg-7 col-md-7 col-sm-7"></div>
		</div>
	</div>
</div>