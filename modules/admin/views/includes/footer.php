<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
   	<footer class="row top-buffer">
  		<div class="col-lg-6">
			Executed in {execution_time} and used {memory_usage} of memory.
		</div>
		<div class="col-lg-6 text-right">
			&copy; <?=date('Y');?>
		</div>
   	</footer>
   	</div><!--.container-->
   	<div id="loading_screen" style="display:none"><img src="<?=url::base();?>assets/images/loading.gif" alt="Spinner" title="Loading - please wait." width="32" /></div>
  </body>
</html>