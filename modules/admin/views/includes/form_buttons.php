<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div style="display:block;border-top:1px solid #DDDDDD;padding-top:20px;margin-top:20px;">
	<div class="btn-group">
		<button name="action" value="save" type="submit" class="btn btn-primary" title="Save and return to the list">Save</button>
		<button name="action" value="update" type="submit" class="btn btn-info" title="Update and stay on page">Update</button>
		<?php if($edit && Auth::instance()->logged_in('admin')) { ?>
			<button id="delete_btn" name="action" value="delete" type="button" class="btn btn-warning" title="Delete this entry">Delete</button>
		<?php } ?>
		<button id="cancel_btn" name="action" value="cancel" type="button" class="btn btn-danger" title="Abort changes and return to the list">Cancel</button>
	</div>
</div>