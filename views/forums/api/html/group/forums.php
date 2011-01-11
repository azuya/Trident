<?php echo form::open(NULL, array('id' => 'forum_group_forums')); ?>
    <h1><?php echo html::chars(Security::xss_clean($group->title)); ?></h1>

	<dl class="fieldlist">
		<!-- Forums -->
        <ul class="standard_list group_forums_list">
        <?php foreach($forums as $forum): ?>
            <li class="standard_item">
                <?php echo form::checkbox('forums[]', $forum->id, $group->has('forum_forums', $forum), array('id' => 'forum_groups_'.$forum->id)); ?>
                
                <?php echo form::label('forum_groups_'.$forum->id, html::chars(Security::xss_clean($forum->title))); ?>
                
            </li>
        
        <?php endforeach?>
        
        <?php echo form::hidden('dummy', 'input'); ?>
        
		<!-- Submit -->
		<dd class="user_submit" id="forum_group_forums_submit"><button type="submit"><span>Submit</span></button></dd>
	</dl>
	
<?php echo form::close(); ?>