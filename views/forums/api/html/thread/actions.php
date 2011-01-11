<ul class="actions thread_actions">
	<?php if($reply): ?><li class="action action_reply"><?php echo html::anchor($reply_route->uri(array('thread_id' => $thread->id)), 'Reply'); ?></li><?php endif; ?>
	<?php if($delete): ?><li class="action action_delete"><?php echo html::anchor($default_route->uri(array('controller' => 'thread', 'action' => 'delete', 'id' => $thread->id)), 'Delete'); ?></li><?php endif; ?>
	<?php if($edit): ?><li class="action action_edit"><?php echo html::anchor($default_route->uri(array('controller' => 'thread', 'action' => 'edit', 'id' => $thread->id)), 'Edit'); ?></li><?php endif; ?>
</ul>