<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<?php echo new View('includes/header'); ?> 
<?php echo new View('includes/header_inner'); ?>

	<h1><?=$section_name;?></h1>
	<?php echo new View('includes/breadcrumbs', array('breadcrumbs' => $breadcrumbs)); ?>
	
	<?php echo new View('includes/alerts'); ?>
		
	<form id="admin_list" action="" method="get">
		<?php echo new View('includes/list_top_row'); ?>
				
		<?php if($data){ ?>
			<table id="admin_list_table" class="table table-striped table-hover">
				<thead>
					<tr>
						<th><a href="javascript:void(0)" id="checkBoxAction" title="Select all entries">+</a></th>
						<?php foreach($columns as $key => $column){ ?>
							<th><a href="<?=url::order($key); ?>" title="Order by <?=$column;?>"><?=$column;?></a></th>
						<?php } ?>
						<th class="text-right">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($data as $k => $v){ ?>
						<tr>
							<td>
								<label><?=form::checkbox(array('id' => 'list_row_checkbox_'.$v[$column_prefix.'id'],'class' => 'list_row_checkbox','name' => 'list_row_checkbox[]'), $v[$column_prefix.'id'], false);?></label>
							</td>						
							<?php foreach($columns as $key => $column){ ?>
								<td><?=$v[$key];?></td>
							<?php } ?>						
							<td class="text-right">
								<a href="<?=url::base().$section_url;?>/edit/<?=$v[$column_prefix.'id'];?>" title="Edit">Edit</a>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		<?php } else { ?>
			<div class="row top-buffer-medium">
				<div class="col-lg-12">
					<p>No data found.</p>
				</div>
			</div>
		<?php } ?>
		
		<?php echo new View('includes/list_bottom_row'); ?>
	</form>
	
<?php echo new View('includes/footer_inner'); ?>
<?php echo new View('includes/footer'); ?>