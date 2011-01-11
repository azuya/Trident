<div class="group">
	<?php echo $actions; ?>
	
	<h3><?php echo html::chars(Security::xss_clean($group->title)); ?></h3>
	<dl class="details">
		
		<dt class="threads">Forums</dt>
			<dd><?php echo $group->get_forum_count(); ?></dd>
		
		<dt class="views">Users</dt>
			<dd><?php echo $group->get_user_count(); ?></dd>
				
	</dl>
</div>