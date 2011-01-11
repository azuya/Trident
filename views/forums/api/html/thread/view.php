<div class="thread" id="forum_thread_<?php echo $thread->id; ?>">
    <?php echo $actions; ?>
	<h1><?php echo html::chars(Security::xss_clean($thread->title)); ?></h1>
	<?php echo $pagination; ?>
	
	<?php if ($pagination->current_page === 1): ?>
		
	<div class="opening_post">
		
		<ul class="post_metadata">
			<li class="post_author"><?php echo html::chars($thread->author->first.' '.$thread->author->last); ?></li>
			<li class="post_date"><?php echo date($date_format, $thread->created); ?></li>
		</ul>
		<div class="post_content">
			<?php echo html::chars($thread->content); ?>
			
		</div>
		<?php if ($thread->created < $thread->last_modified): ?>

		<div class="last_modified">
			<p>Last modified on <?php echo date($date_format, $thread->last_modified); ?></p>
		</div>
		<?php endif;?>
		
	</div>
	<?php endif; ?>
	
	<ul class="standard_list thread_replies">
		<?php foreach($posts as $post): ?>
			
		<li class="standard_list_item reply_list_item">
			<?php echo $post; ?>
			
		</li>
		<?php endforeach; ?>
	</ul>
	
	<?php echo $pagination; ?>
	
</div>