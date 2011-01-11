<ul class="actions forum_actions">
	<?php if($new_thread): ?><li class="action action_new_thread"><?php echo html::anchor($new_thread_route->uri(array('forum_id' => $forum->id)), 'New Thread'); ?></li><?php endif; ?>
	<?php if($delete): ?><li class="action action_delete"><?php echo html::anchor($default_route->uri(array('controller' => 'forum', 'action' => 'delete', 'id' => $forum->id)), 'Delete'); ?></li><?php endif; ?>
	<?php if($edit): ?><li class="action action_edit"><?php echo html::anchor($default_route->uri(array('controller' => 'forum', 'action' => 'edit', 'id' => $forum->id)), 'Edit'); ?></li><?php endif; ?>
</ul>