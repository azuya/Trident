<ul class="actions category_actions">
	<?php if($new_forum): ?><li class="action action_new_forum"><?php echo html::anchor($new_forum_route->uri(array('category_id' => $category->id)), 'New Forum'); ?></li><?php endif; ?>
	<?php if($delete): ?><li class="action action_delete"><?php echo html::anchor($default_route->uri(array('controller' => 'category', 'action' => 'delete', 'id' => $category->id)), 'Delete'); ?></li><?php endif; ?>
	<?php if($edit): ?><li class="action action_edit"><?php echo html::anchor($default_route->uri(array('controller' => 'category', 'action' => 'edit', 'id' => $category->id)), 'Edit'); ?></li><?php endif; ?>
</ul>