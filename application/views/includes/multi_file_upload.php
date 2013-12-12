<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div class="multi_uploader" data-type="<?=$type;?>" data-files="<?=(isset($files) && $files ? htmlentities(json_encode($files)) : 'false');?>">
	<!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
	<div class="row fileupload-buttonbar">
		<div class="col-lg-12">
			<!-- The fileinput-button span is used to style the file input field as button -->
			<span class="btn btn-primary fileinput-button">
				<i class="glyphicon glyphicon-plus"></i>
				<span>Add files</span>
				<input type="file" name="files[]" multiple>
			</span>
			<button type="submit" class="btn btn-primary start" style="display:none;">
				<i class="glyphicon glyphicon-upload"></i>
				<span>Start upload</span>
			</button>
			<button type="reset" class="btn btn-warning cancel" style="display:none;">
				<i class="glyphicon glyphicon-ban-circle"></i>
				<span>Cancel upload</span>
			</button>
			<div style="display:none;">
				<button type="button" class="btn btn-danger delete">
					<i class="glyphicon glyphicon-trash"></i>
					<span>Delete</span>
				</button>
				<input type="checkbox" class="toggle">
			</div>
			<!-- The loading indicator is shown during file processing -->
			<span class="fileupload-loading"></span>
		</div>
	</div>
	
	<div class="row fileupload-progress fade" style="display:none;">
		<!-- The global progress information -->
		<div class="col-lg-12">
			<!-- The global progress bar -->
			<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
				<div class="progress-bar progress-bar-success" style="width:0%;"></div>
			</div>
			<!-- The extended global progress information -->
			<div class="progress-extended">&nbsp;</div>
		</div>
	</div>
	
	<!-- The table listing the files available for upload/download -->
	<table class="fileupload-table table table-striped table-hover table-condensed" width="100%" role="presentation" style="display:none;">
		<thead>
			<tr>
				<th></th>
				<th width="100px">File type</th>
				<th>File name</th>
				<th width="100px">File size</th>
				<th width="100px">File owner</th>
				<th width="100px">File upload</th>
				<th width="180px" align="right"></th>
			</tr>
		</thead>
		<tbody class="files"></tbody>
	</table>
</div>