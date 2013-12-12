<?php defined('SYSPATH') OR die('No direct access allowed.');

echo new View('includes/header'); ?>

<div class="row">
	<div class="col-lg-6 col-lg-offset-3">
		<?php echo form::open(); ?>
			<?php print form::open_fieldset(); ?>
			<?php print form::legend('Register'); ?>
			
			<div class="form-group">
				<?php echo form::label('email', 'Email: <span class="req">*</span>'); ?>
				<?php echo form::input(array('name' => 'email','class' => 'form-control','maxlength' => 127), @$_POST['email']); ?>
			</div>
			
			<div class="form-group">
				<?php echo form::label('username', 'Username: <span class="req">*</span>'); ?>
				<?php echo form::input(array('name' => 'username','class' => 'form-control','maxlength' => 32), @$_POST['username']); ?>
			</div>
			
			<div class="form-group">
				<?php echo form::label('password', 'Password: <span class="req">*</span>'); ?>
				<?php echo form::password(array('name' => 'password','class' => 'form-control','maxlength' => 42), @$_POST['password']); ?>
			</div>
			
			<div class="form-group">
				<?php echo form::label('password_confirm', 'Password confirm: <span class="req">*</span>'); ?>
				<?php echo form::password(array('name' => 'password_confirm','class' => 'form-control','maxlength' => 42), @$_POST['password_confirm']); ?>
			</div>
			
			<?php echo form::submit(array('class' => 'btn btn-default'), 'Register'); ?>
			
			<?php print form::close_fieldset(); ?>
		<?php echo form::close(); ?>
	</div>
</div>

<?php echo new View('includes/footer'); ?>