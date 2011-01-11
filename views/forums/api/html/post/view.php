<div class="post" id="forum_post_<?php echo $post->id; ?>">
	<?php echo $actions; ?>
	<ul class="post_metadata">
		<li class="post_author"><?php echo html::chars($post->author->first.' '.$post->author->last); ?></li>
		<li class="post_date"><?php echo date($date_format, $post->created); ?></li>
	</ul>
	<div class="post_content">
		<?php echo html::chars($post->content); ?>
	</div>
	<?php if ($post->created < $post->last_modified): ?>
	
	<div class="last_modified">
		<p>Last modified on <?php echo date($date_format, $post->last_modified); ?></p>
	</div>
	<?php endif;?>
	
</div>