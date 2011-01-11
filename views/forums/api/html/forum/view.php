<div class="forum" id="forum_forum_<?php echo $forum->id; ?>">
	<?php echo $actions; ?>
	
	<h1><?php echo html::chars(Security::xss_clean($forum->title)); ?></h1>
	
	<?php echo $pagination; ?>
	
	<ul class="standard_list forum_threads">
		<?php foreach($threads as $thread): ?>
			
		<li class="standard_list_item thread_list_item">
			<?php echo $thread; ?>
			
		</li>
		<?php endforeach; ?>
	</ul>
	
	<?php echo $pagination; ?>
	
</div>