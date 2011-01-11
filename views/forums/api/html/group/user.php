<?php echo form::open(NULL, array('id' => 'forum_group_users')); ?>
    <h1><?php echo html::chars(Security::xss_clean($user->username)); ?> (<?php echo html::chars(Security::xss_clean($user->first.' '.$user->last)); ?>): Groups</h1>

	<dl class="fieldlist">
		<!-- Forums -->
        <ul class="standard_list groups_users_list">
        <?php foreach($groups as $group): ?>
            <li class="standard_item">
                <?php echo form::checkbox('groups[]', $group->id, $group->has('users', $user), array('id' => 'users_groups_'.$group->id)); ?>
                
                <?php echo form::label('users_groups_'.$group->id, html::chars(Security::xss_clean($group->title))); ?>
                
            </li>
        
        <?php endforeach?>
        
        <?php echo form::hidden('dummy', 'input'); ?>
        
		<!-- Submit -->
		<dd class="user_submit" id="forum_group_users_submit"><button type="submit"><span>Submit</span></button></dd>
	</dl>
	
<?php echo form::close(); ?>