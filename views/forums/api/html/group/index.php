<div class="forum_group_index">
    <ul class="actions group_index_actions">
        <?php if ($new_group): ?><li class="action action_new_group"><?php echo html::anchor($default_route->uri(array('controller' => 'group', 'action' => 'new')), 'New Group'); ?></li><?php endif; ?>
    </ul>
    <h1>Groups</h1>
    <ul class="standard_list forum_categories_list">
        <?php foreach($groups as $group): ?>
            
        <li class="standard_item forum_group">
        <?php echo $group; ?>
        
        </li>
        <?php endforeach; ?>
    </ul>
</div>