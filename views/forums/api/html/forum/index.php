<div class="forum_index">
    <ul class="actions forum_index_actions">
        <?php if ($new_category): ?><li class="action action_new_category"><?php echo html::anchor($default_route->uri(array('controller' => 'category', 'action' => 'new')), 'New Category'); ?></li><?php endif; ?>
        <?php if ($manage_groups): ?><li class="action action_manage_groups"><?php echo html::anchor($default_route->uri(array('controller' => 'group', 'action' => 'index')), 'Manage Groups'); ?></li><?php endif; ?>
    </ul>
    <h1><?php echo html::chars(Security::xss_clean($forum_title)); ?></h1>
    <ul class="standard_list forum_categories_list">
        <?php foreach($categories as $category): ?>
        
        <?php //if ($category !== ''): ?>
        
        <li class="standard_item forum_category">
        <?php echo $category; ?>
        
        </li>
        <?php //endif; ?>
        
        <?php endforeach; ?>
    </ul>
</div>