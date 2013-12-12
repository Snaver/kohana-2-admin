<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<?php echo new View('includes/header'); ?> 
<?php echo new View('includes/header_inner'); ?>

	<h1><?=$section_name;?><?php if($edit){ ?><span class="pull-right"><?=text::limit_chars($item_name,19,'',false);?></span><?php } ?></h1>
	<?php echo new View('includes/breadcrumbs', array('breadcrumbs' => $breadcrumbs)); ?>
	
	<?php echo new View('includes/alerts'); ?>
	
	<form id="admin_form" action="" method="post" class="form-horizontal admin_form <?=$section_url;?>_area" role="form" autocomplete="off" enctype="multipart/form-data">
		<ul id="" class="nav nav-tabs">
			<?php foreach($tabs as $k => $tab){ ?>
				<?php if(!in_array($k,$hide_tabs)) { ?>
					<li class="<?=($k == $active_tab ? 'active' : '');?>"><a href="#tab-<?=$k;?>" data-toggle="tab"><?=$tab;?></a></li>
				<?php } ?>
			<?php } ?>
		</ul>
		<div class="tab-content">
			<?php foreach($tabs as $k => $tab){ ?>
				<?php if(!in_array($k,$hide_tabs)) { ?>
					<div class="tab-pane fade <?=($k == $active_tab ? 'active in' : '');?>" id="tab-<?=$k;?>">
						<?=new View($section_url.'/tab-'.$k);?>
					</div>
				<?php } ?>
			<?php } ?>
		</div>	

		<div class="row">
			<div class="col-lg-12">
				<?php echo new View('includes/form_buttons', array('edit' => $edit)); ?>
			</div>
		</div>
	</form>
	
	<?php echo new View('includes/file_upload_js_templates'); ?>

<?php echo new View('includes/footer_inner'); ?>
<?php echo new View('includes/footer'); ?>