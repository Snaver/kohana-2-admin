jQuery.fn.toggleClick = function(){

    var functions = arguments ;

    return this.click(function(){
            var iteration = $(this).data('iteration') || 0;
            functions[iteration].apply(this, arguments);
            iteration = (iteration + 1) % functions.length ;
            $(this).data('iteration', iteration);
    });
};

tmpl.helper += ",log=function(){console.log.apply(console, arguments)}" +",st='',stream=function(cb){var l=st.length;st=_s;cb( _s.slice(l));}";

// http://kevin.vanzonneveld.net
// +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
// + namespaced by: Michael White (http://getsprink.com)
// +      input by: Marco van Oort
// +   bugfixed by: Brett Zamir (http://brett-zamir.me)
// *     example 1: str_pad('Kevin van Zonneveld', 30, '-=', 'STR_PAD_LEFT');
// *     returns 1: '-=-=-=-=-=-Kevin van Zonneveld'
// *     example 2: str_pad('Kevin van Zonneveld', 30, '-', 'STR_PAD_BOTH');
// *     returns 2: '------Kevin van Zonneveld-----'
function str_pad (input, pad_length, pad_string, pad_type) {
  var half = '',
    pad_to_go;

  var str_pad_repeater = function (s, len) {
    var collect = '',
      i;

    while (collect.length < len) {
      collect += s;
    }
    collect = collect.substr(0, len);

    return collect;
  };

  input += '';
  pad_string = pad_string !== undefined ? pad_string : ' ';

  if (pad_type !== 'STR_PAD_LEFT' && pad_type !== 'STR_PAD_RIGHT' && pad_type !== 'STR_PAD_BOTH') {
    pad_type = 'STR_PAD_RIGHT';
  }
  if ((pad_to_go = pad_length - input.length) > 0) {
    if (pad_type === 'STR_PAD_LEFT') {
      input = str_pad_repeater(pad_string, pad_to_go) + input;
    } else if (pad_type === 'STR_PAD_RIGHT') {
      input = input + str_pad_repeater(pad_string, pad_to_go);
    } else if (pad_type === 'STR_PAD_BOTH') {
      half = str_pad_repeater(pad_string, Math.ceil(pad_to_go / 2));
      input = half + input + half;
      input = input.substr(0, pad_length);
    }
  }

  return input;
}

function init_jquery_upload_multiple(element){	
    // Setup fileupload
    var uploader = jQuery(element).fileupload({
    	dropZone			: null, // Disable drag & drop support
		pasteZone			: null, // Disable paste support
        formData			: {type : jQuery(element).data('type')},
        url					: url_base+'files/upload',
        acceptFileTypes		: /(\.|\/)(gif|jpe?g|png|psd|tiff|bmp|eps|ai|pdf|doc|docx|xls|xlsx|ppt|pptx|zip|rar|txt)$/i,
        maxFileSize			: 104857600, // 100 MB
        uploadTemplateId	: 'template-upload', // Defaults just for reference
        downloadTemplateId	: 'template-download' // Defaults just for reference
    });
    
    // Callbacks
    jQuery(element).on('fileuploadadd', function (e, data) {
    	jQuery(e.target).find('.fileupload-buttonbar .btn.btn-primary.start').show();
    	jQuery(e.target).find('.fileupload-table').show();
    });
    jQuery(element).on('fileuploadstart', function (e, data) {
    	jQuery(e.target).find('.fileupload-progress').show();
    	jQuery(e.target).find('.fileupload-buttonbar .btn.btn-warning.cancel').show();
    });
    jQuery(element).on('fileuploadstop', function (e, data) {
    	jQuery(e.target).find('.fileupload-progress').hide();
    	jQuery(e.target).find('.fileupload-buttonbar .btn.btn-primary.start').hide();
    	jQuery(e.target).find('.fileupload-buttonbar .btn.btn-warning.cancel').hide();
    });
    
    // Load files in to table
    if(jQuery(element).data('files')){
    	jQuery(element).fileupload('option', 'done').call(element, null, {result: jQuery(element).data('files')});
    	
    	jQuery(element).find('.fileupload-table').show();
    }
    
    return;
}

function deinit_jquery_upload_single(element){
	// Remove callbacks
	jQuery(document).off('fileuploadstart', element);
	jQuery(document).off('fileuploadadded', element);
	jQuery(document).off('fileuploadalways', element);
	
	// Destroy fileupload
	jQuery(element).fileupload('destroy');
	
	return;
}

function init_jquery_upload_single(element){
	var download_id = jQuery(element).data('download_id') ? jQuery(element).data('download_id') : 'template-download-single';
	
	// Setup fileupload
	var uploader = jQuery(element).fileupload({
		dropZone			: null, // Disable drag & drop support
		pasteZone			: null, // Disable paste support
		url					: url_base+'files/upload',
		formData			: {field : jQuery(element).data('field')},
		acceptFileTypes		: /(\.|\/)(gif|jpe?g|png|psd|tiff|bmp|eps|ai|pdf|doc|docx|xls|xlsx|ppt|pptx|zip|rar|txt)$/i,
		maxFileSize			: 104857600, // 100 MB
		autoUpload 			: true,
		sequentialUploads 	: true,
		uploadTemplateId	: 'template-upload-single',
        downloadTemplateId	: download_id
	});
	
	// Callbacks
	jQuery(element).on('fileuploadstart', function (e, data) {	
    	jQuery(e.target).find('.btn.btn-primary i').hide();
    	jQuery(e.target).find('.btn.btn-primary span').hide();
    	jQuery(e.target).find('.btn.btn-primary').append('<img src="'+url_base+'assets/images/loading.gif" alt="Loading" title="Uploading" width="18" height="18" />');
    
    	// Disable uploading while current upload in progress
    	jQuery(e.target).find('input[type="file"]').prop('disabled', true);
    });
    jQuery(element).on('fileuploadadded', function (e, data) {
    	// Check for validation errors
		jQuery.each(data.files, function (index, file) {
			if (file.hasOwnProperty ('error')) {
				uploadSingleError(e.target,file.error);
			}
		});
		
		// Bug with JS template being appended more than once 
		jQuery(e.target).find('.files > div *').remove();
    });
    jQuery(element).on('fileuploadalways', function (e, data) {
    	if(data.textStatus == 'success'){
    		if(typeof data.result.files[0].error != 'undefined'){
    			uploadSingleError(e.target,data.result.files[0].error);
    		} else {
    			jQuery(e.target).find('.btn.btn-primary img').remove();
	    		jQuery(element).find('.btn.btn-primary i').removeClass('glyphicon-ok').addClass('glyphicon-plus').show();
				jQuery(element).find('.btn.btn-primary span').text('Upload file').show();
				
				jQuery(element).find('.btn.btn-file-uploaded').show();
    		}
    	} else {
    		uploadSingleError(e.target,'Server error');
    	}
    	
    	// Enable uploading when finished
    	jQuery(e.target).find('input[type="file"]').prop('disabled', false);
    });
    
    // Load file back if present
    if(jQuery(element).data('files')){
    	jQuery(element).fileupload('option', 'done').call(element, null, {result: jQuery(element).data('files')});
    }
    
    return;
}

function uploadSingleError(element,message){
	message = typeof message !== 'undefined' ? message : '';
	
	resetUploader(element);
	
	alert('Sorry, there was a problem uploading your file. '+message);
}

function resetUploader(element){
	jQuery(element).find('.btn img').remove();
	jQuery(element).find('.btn i').removeClass('glyphicon-ok').addClass('glyphicon-plus').show();
	jQuery(element).find('.btn span').text('Upload file').show();
	jQuery(element).find('.files').html('');
}

function loadingScreen(show){
	show = typeof show !== 'undefined' ? show : false;
	
	if(show){
		jQuery('#loading_screen').show();
	} else {
		jQuery('#loading_screen').hide();
	}
}


