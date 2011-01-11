<ul class="actions post_actions">
	<?php if($delete): ?><li class="action action_delete"><?php echo html::anchor($default_route->uri(array('controller' => 'post', 'action' => 'delete', 'id' => $post->id)), 'Delete'); ?></li><?php endif; ?>
	<?php if($edit): ?><li class="action action_edit"><?php echo html::anchor($default_route->uri(array('controller' => 'post', 'action' => 'edit', 'id' => $post->id)), 'Edit'); ?></li><?php endif; ?>
</ul>