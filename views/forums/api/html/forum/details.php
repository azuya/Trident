<div class="forum<?php echo $activity ? ' active' : ''; ?>">
	<?php echo $actions; ?>
	
	<h3><?php echo html::anchor($default_route->uri(array('controller' => 'forum', 'action' => 'view', 'id' => $forum->id)), html::chars(Security::xss_clean($forum->title))); ?></h3>
	<p><?php echo html::chars(Security::xss_clean($forum->description)); ?></p>
	<dl class="details">
		
		<dt class="threads">Threads</dt>
			<dd><?php echo $forum->get_thread_count(); ?></dd>
		
		<dt class="views">Replies</dt>
			<dd><?php echo $forum->get_post_count(); ?></dd>
		
		<dt class="posted">Last Reply</dt>
			<dd><?php echo $latest_thread === FALSE ? 'n/a' : html::anchor($default_route->uri(array('controller' => 'thread', 'action' => 'view', 'id' => $latest_thread->id)).'?page=last', html::chars(Security::xss_clean($latest_thread->title))); ?></dd>
		
		<dt class="updated">By</dt>
			<dd><?php echo $latest_thread === FALSE ? 'n/a' : html::anchor('user/'.$latest_thread->author->id.'/view', html::chars(Security::xss_clean($latest_thread->author->first.' '.$latest_thread->author->last))); ?></dd>
			
	</dl>
	
</div>