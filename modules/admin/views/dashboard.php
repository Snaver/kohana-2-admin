<?php defined('SYSPATH') OR die('No direct access allowed.');

echo new View('includes/header'); 
echo new View('includes/header_inner'); ?>

<h1>Welcome back</h1>
<div class="row">
	<div class="col-lg-6">
		<div class="panel panel-default">
			<div class="panel-heading"><strong>Panel 1</strong></div>
			<div class="panel-body">
				<p>Nulla euismod quis ipsum ac molestie. Proin accumsan elit quis ante convallis, sed auctor nulla dictum. Etiam in mauris nec est porta.</p>
			</div>
			<table class="table">
	          <thead>
	            <tr>
	              <th>#</th>
	              <th>First Name</th>
	              <th>Last Name</th>
	              <th>Username</th>
	            </tr>
	          </thead>
	          <tbody>
	            <tr>
	              <td>1</td>
	              <td>Mark</td>
	              <td>Otto</td>
	              <td>@mdo</td>
	            </tr>
	            <tr>
	              <td>2</td>
	              <td>Jacob</td>
	              <td>Thornton</td>
	              <td>@fat</td>
	            </tr>
	            <tr>
	              <td>3</td>
	              <td>Larry</td>
	              <td>the Bird</td>
	              <td>@twitter</td>
	            </tr>
	          </tbody>
	        </table>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="panel panel-default">
			<div class="panel-heading"><strong>Panel 2</strong></div>
			<div class="panel-body">
				<p>Proin egestas fringilla mauris, nec tincidunt odio auctor et. Suspendisse nunc magna, pellentesque ut vestibulum mattis, posuere.</p>
			</div>
			<table class="table">
	          <thead>
	            <tr>
	              <th>#</th>
	              <th>First Name</th>
	              <th>Last Name</th>
	              <th>Username</th>
	            </tr>
	          </thead>
	          <tbody>
	            <tr>
	              <td>1</td>
	              <td>Mark</td>
	              <td>Otto</td>
	              <td>@mdo</td>
	            </tr>
	            <tr>
	              <td>2</td>
	              <td>Jacob</td>
	              <td>Thornton</td>
	              <td>@fat</td>
	            </tr>
	            <tr>
	              <td>3</td>
	              <td>Larry</td>
	              <td>the Bird</td>
	              <td>@twitter</td>
	            </tr>
	          </tbody>
	        </table>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading"><strong>Full panel	</strong></div>
			<div class="panel-body">
				<p>Vestibulum risus lectus, porttitor quis odio eget, tincidunt mattis tellus. </p>
			</div>
		</div>
	</div>
</div>

<?php echo new View('includes/footer_inner'); ?>
<?php echo new View('includes/footer'); ?>