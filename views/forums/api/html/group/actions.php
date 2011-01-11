<ul class="actions group_actions">
    <?php if($assign): ?><li class="action action_assign"><?php echo html::anchor($default_route->uri(array('controller' => 'group', 'action' => 'forums', 'id' => $group->id)), 'Assign Forums'); ?></li><?php endif; ?>
	<?php if($delete): ?><li class="action action_delete"><?php echo html::anchor($default_route->uri(array('controller' => 'group', 'action' => 'delete', 'id' => $group->id)), 'Delete'); ?></li><?php endif; ?>
	<?php if($edit): ?><li class="action action_edit"><?php echo html::anchor($default_route->uri(array('controller' => 'group', 'action' => 'edit', 'id' => $group->id)), 'Edit'); ?></li><?php endif; ?>
</ul>