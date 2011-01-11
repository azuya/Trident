<?php echo form::open(NULL, array('id' => 'forum_thread')); ?>

	<dl class="fieldlist">
		
		<!-- Title -->
		<dt><?php echo form::label('title', 'Title'); ?></dt>
			<?php if (array_key_exists('title', $errors)): ?><dd class="error"><?php echo $errors['title']; ?></dd><?php endif; ?>
			
			<dd class="user_input" id="forum_thread_title"><?php echo form::input('title', array_key_exists('title', $data) ? html::chars(Security::xss_clean($data['title'])) : ''); ?></dd>
			
		<!-- Content -->
		<dt><?php echo form::label('content', 'Opening Post'); ?></dt>
			<?php if (array_key_exists('content', $errors)): ?><dd class="error"><?php echo $errors['content']; ?></dd><?php endif; ?>
			
			<dd class="user_input" id="forum_thread_content"><?php echo form::textarea('content', array_key_exists('content', $data) ? html::chars(Security::xss_clean($data['content'])) : ''); ?></dd>
		
		<!-- Submit -->
		<dd class="user_submit" id="forum_thread_submit"><button type="submit"><span>Submit</span></button></dd>
	</dl>
	
<?php echo form::close(); ?>