<div class="thread<?php echo $activity ? ' active' : ''; ?>">
	<?php echo $actions; ?>
	
	<h3><?php echo html::anchor($default_route->uri(array('controller' => 'thread', 'action' => 'view', 'id' => $thread->id)), html::chars(Security::xss_clean($thread->title))); ?></h3>
	
	<dl class="details">
		
		<dt class="replies">Replies</dt>
			<dd><?php echo $thread->get_replies_count(); ?></dd>
		
		<dt class="views">Views</dt>
			<dd><?php echo $thread->get_views_count(); ?></dd>
		
		<dt class="posted">Date Posted</dt>
			<dd><?php echo date($date_format, $thread->created); ?></dd>
		
		<dt class="author">Author</dt>
		    <dd><?php echo html::anchor('user/view/'.$thread->author->id, $thread->author->first.' '.$thread->author->last); ?></dd>
		
		<dt class="updated">Last Post</dt>
			<dd><?php echo $latest_post === FALSE ? date($date_format, $thread->created) : date($date_format, $latest_post->created); ?></dd>
			
		<dt class="updated_by">By</dt>
		    <dd><?php echo $latest_post === FALSE ? html::anchor('user/view/'.$thread->author->id, $thread->author->first.' '.$thread->author->last) : html::anchor('user/view/'.$latest_post->author->id, $latest_post->author->first.' '.$latest_post->author->last); ?></dd>
			
	</dl>
	
</div>