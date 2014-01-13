<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade {% if (file.error) { %}danger{% } %}">
        <td colspan="2">
        	{% if (file.error) { %}
                <span class="label label-danger">Error</span> {%=file.error%}
            {% } else { %}
	            {% if (file.type == 'image/jpg') { %}
	            	Image
	            {% } else if (file.type == 'image/jpeg') { %}
	            	Image
	            {% } else if (file.type == 'image/png') { %}
	            	Image
	            {% } else if(file.type == 'application/pdf') { %}
	            	Document
	            {% } else { %}
	            	Unknown	
	            {% } %}
            {% } %}
        </td>
        <td>
            <span class="name">{%=file.name%}</span>
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td align="right" colspan="3">
            {% if (!o.files.error && !i && !o.options.autoUpload) { %}
                <button class="btn btn-primary btn-sm start">
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning btn-sm cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>

<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade {% if (file.error) { %}danger{% } %}">
    	<td>
    		<input type="checkbox" name="delete" value="1" class="toggle">
    		<input type="hidden" name="files[id][]" value="{%=file.id%}" />
    		<input type="hidden" name="files[name][]" value="{%=file.name%}" />
    		<input type="hidden" name="files[system_name][]" value="{%=file.system_name%}" />
    		<input type="hidden" name="files[type][]" value="{%=file.type%}" />
    		<input type="hidden" name="files[mime_type][]" value="{%=file.mime_type%}" />
    		<input type="hidden" name="files[extension][]" value="{%=file.extension%}" />
    		<input type="hidden" name="files[size][]" value="{%=file.size%}" />
    	</td>
        <td>
            {% if (file.error) { %}
                <span class="label label-danger">Error</span> {%=file.error%}
            {% } else { %}
	            {% if (file.mime_type == 'image/jpg') { %}
	            	Image
	            {% } else if (file.mime_type == 'image/jpeg') { %}
	            	Image
	            {% } else if (file.mime_type == 'image/png') { %}
	            	Image
	            {% } else if(file.mime_type == 'application/pdf') { %}
	            	Document
	            {% } else { %}
	            	Unknown
	            {% } %}
            {% } %}
        </td>
        <td>
            <span class="name">{%=file.name%}</span>
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
         <td>
            <span class="owner">{% if (file.user_username) { %} {%=file.user_username%} {% } else { %} You {% } %}</span>
        </td>
        <td>
            <span class="uploaded">{% if (file.created_date) { %} {%=moment(file.created_date, 'YYYY-MM-DD hh:mm:ss').fromNow()%} {% } else { %} a few seconds ago {% } %}</span>
        </td>
        <td align="right">
        	{% if (file.id) { %}
        	<a href="<?=url::base();?>files/download/{%=file.token%}" target="_blank" class="btn btn-success btn-sm download">
                <i class="glyphicon glyphicon-download"></i>
                <span>Download</span>
            </a>
            {% } %}
            <button class="btn btn-danger btn-sm delete">
                <i class="glyphicon glyphicon-trash"></i>
                <span>Delete</span>
            </button>
        </td>
    </tr>
{% } %}
</script>

<script id="template-upload-single" type="text/x-tmpl">
	{% for (var i=0, file; file=o.files[i]; i++) { log(file); %}
		<div class="template-upload">
			{% if (file.token) { %}
				<a href="<?=url::base();?><?=$section_url;?>/download/{%=file.token%}" target="_blank" class="btn btn-success btn-download-file" title="Download {%=file.name%}">
					<i class="glyphicon glyphicon-download"></i>
					<span>Download file</span>
				</a>
			{% } %}
			<span class="btn btn-success btn-file-uploaded" title="File uploaded" style="{% if (file.token) { %}display:none;{% } %}">
				<i class="glyphicon glyphicon-ok"></i>
			</span>
			
			<input type="hidden" name="{%=file.field%}[file_name]" value="{%=file.name%}" />
			<input type="hidden" name="{%=file.field%}[file_system_name]" value="{%=file.system_name%}" />
			<input type="hidden" name="{%=file.field%}[file_mime_type]" value="{%=file.mime_type%}" />
			<input type="hidden" name="{%=file.field%}[file_extension]" value="{%=file.extension%}" />
			<input type="hidden" name="{%=file.field%}[file_size]" value="{%=file.size%}" />
		</div>
	{% } %}
</script>

<script id="template-download-single" type="text/x-tmpl">
	{% for (var i=0, file; file=o.files[i]; i++) { log(file); %}
		<div class="template-download">
			{% if (file.token) { %}
				<a href="<?=url::base();?>files/download/{%=file.token%}" target="_blank" class="btn btn-success btn-download-file" title="Download {%=file.name%}">
					<i class="glyphicon glyphicon-download"></i>
					<span>Download file</span>
				</a>
			{% } %}
			<span class="btn btn-success btn-file-uploaded" title="File uploaded" style="{% if (file.token) { %}display:none;{% } %}">
				<i class="glyphicon glyphicon-ok"></i>
			</span>
			
			<input type="hidden" name="{%=file.field%}[file_field]" value="{%=file.field%}" />
			<input type="hidden" name="{%=file.field%}[file_name]" value="{%=file.name%}" />
			<input type="hidden" name="{%=file.field%}[file_system_name]" value="{%=file.system_name%}" />
			<input type="hidden" name="{%=file.field%}[file_mime_type]" value="{%=file.mime_type%}" />
			<input type="hidden" name="{%=file.field%}[file_extension]" value="{%=file.extension%}" />
			<input type="hidden" name="{%=file.field%}[file_size]" value="{%=file.size%}" />
		</div>
	{% } %}
</script>
<script id="template-download-single-orders" type="text/x-tmpl">
	{% for (var i=0, file; file=o.files[i]; i++) { log(file); %}
		<div class="template-download">
			<span class="btn btn-success btn-file-uploaded" title="File uploaded">
				<i class="glyphicon glyphicon-ok"></i>
			</span>
			
			<input type="hidden" name="{%=file.field%}[file_field][]" value="{%=file.field%}" />
			<input type="hidden" name="{%=file.field%}[file_name][]" value="{%=file.name%}" />
			<input type="hidden" name="{%=file.field%}[file_system_name][]" value="{%=file.system_name%}" />
			<input type="hidden" name="{%=file.field%}[file_mime_type][]" value="{%=file.mime_type%}" />
			<input type="hidden" name="{%=file.field%}[file_extension][]" value="{%=file.extension%}" />
			<input type="hidden" name="{%=file.field%}[file_size][]" value="{%=file.size%}" />
		</div>
	{% } %}
</script>