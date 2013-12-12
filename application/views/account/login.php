<?php defined('SYSPATH') OR die('No direct access allowed.');

echo new View('includes/header'); ?>

<?php if($this->session->get('alert')){ $alert = $this->session->get('alert'); ?>
	<div class="row top-buffer">
		<div class="col-lg-6 col-lg-offset-3">
			<div class="alert alert-<?=$alert['type'];?>"><?=$alert['message'];?></div>
		</div>
	</div>
<?php $this->session->delete('alert'); } ?>
<div class="row">
	<div class="col-lg-6 col-lg-offset-3">	 
		<?php echo form::open(url::current(),array('role' => 'form')); ?>
			<?php print form::open_fieldset(); ?>
			<?php print form::legend('Login'); ?>
			
			<div class="form-group">
				<?php echo form::label('username', 'Username: <span class="req">*</span>'); ?>
				<?php echo form::input(array('name' => 'username','class' => 'form-control','maxlength' => 32), @$_POST['username']); ?>
			</div>
			
			<div class="form-group">
				<?php echo form::label('password', 'Password: <span class="req">*</span>'); ?>
				<?php echo form::password(array('name' => 'password','class' => 'form-control','maxlength' => 42), @$_POST['password']); ?>
			</div>
			
			<div class="checkbox">
		      <label>
		        <?php print form::checkbox('remember_me', 1); ?> Remember me
		      </label>
		    </div>
			
			<?php echo form::submit(array('class' => 'btn btn-default'), 'Login'); ?>
			
			<?php print form::close_fieldset(); ?>
		<?php echo form::close(); ?>
	</div>
</div>

<?php echo new View('includes/footer'); ?>