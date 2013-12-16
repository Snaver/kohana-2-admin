jQuery(document).ready(function($) {
	
	// File uploaders init
	jQuery('.multi_uploader').each(function( index ) {
		init_jquery_upload_multiple(this);
	});
	jQuery('.single_uploader').each(function( index ) {
		init_jquery_upload_single(this);
	});
	
	jQuery('.field_input_date .form-control').datetimepicker({
		pickTime: false
	});
	
	jQuery(".alert").alert();
	
	jQuery('body').popover({
		'placement'	: 'auto left',
		'trigger'	: 'focus',
		'selector'	: '.admin_form input, .admin_form select, .admin_form textarea',
		'html'		: true
	});
	
	// Set focus on first form field
	jQuery(".admin_form input, .admin_form select, .admin_form textarea").first().focus();
	
	jQuery('#per_page,#filter').change(function(){
		jQuery(this).closest('form').trigger('submit');
	});
	
	jQuery('#delete_btn').on('click',function(event){
		var form = jQuery(this).parents('form');
		
		bootbox.confirm("Are you sure you wish to delete this entry?", function(result) {
			if(result){
				var input = jQuery("<input>").attr("name", "action").attr("type", "hidden").val("delete");
				
				jQuery(form).append(jQuery(input)).submit();
			}
		});
	});
	
	jQuery('#cancel_btn').on('click',function(event){
		var form = jQuery(this).parents('form');
		
		bootbox.confirm("Are you sure? You will lose all changes.", function(result) {
			if(result){
				var input = jQuery("<input>").attr("name", "action").attr("type", "hidden").val("cancel");
				
				jQuery(form).append(jQuery(input)).submit();
			}
		});
		
		/*bootbox.dialog({
			title: "Confirm",
			message: "Are you sure you wish to cancel? You will lose all changes.",			
			buttons: {
				ok: {
					label: "Ok",
					className: "btn-primary",
					callback: function() {mmm
						
					}
				}
			}
		});*/
	});
	
	// If one or more checked, show the actions dropdown
	jQuery('#admin_list_table .list_row_checkbox').on('change', function(){
		if (jQuery("#admin_list :checkbox[name='list_row_checkbox[]']").is(":checked"))
		{
		    jQuery('#list_actions').show();
		}
		else
		{
		    jQuery('#list_actions').hide();
		}
	});
	
	// Toggle check all
	jQuery('#checkBoxAction').toggleClick(function() {
		jQuery(this).text('-').attr('title','Deselect all entries');
		jQuery('#admin_list_table .list_row_checkbox').prop('checked', true).change();
	}, function() {
		jQuery(this).text('+').attr('title','Select all entries');
		jQuery('#admin_list_table .list_row_checkbox').prop('checked', false).change();
	});
	
	jQuery('#list_actions').on('change', function(){
		var form = jQuery(this).parents('form');

		switch(jQuery(this).val()){
			case "status-0":
				var message = 'Are you sure you want to Unarchive this item(s)?';
			break;
			case "status-1":
				var message = 'Are you sure you want to Archive this item(s)?';
			break;
			case "delete-0":
				var message = 'Are you sure you want to Un-Delete this item(s)?';
			break;
			case "delete-1":
				var message = 'Are you sure you want to Delete this item(s)?';
			break;
			case "delete-2":
				var message = 'Are you sure you want to Permanently Delete this item(s)?';
			break;
		}
		
		bootbox.confirm(message, function(result) {
			if(result){
				var input = jQuery("<input>").attr("name", "action").attr("type", "hidden").val("cancel");
				
				jQuery(form).append(jQuery(input)).submit();
			} else {
				jQuery('#list_actions').val('');
			}
		});
	});
	
});