<?php echo form::open(NULL, array('id' => 'forum_post')); ?>

	<dl class="fieldlist">
		
		<!-- Content -->
		<dt><?php echo form::label('content', 'Post'); ?></dt>
			<?php if (array_key_exists('content', $errors)): ?><dd class="error"><?php echo $errors['content']; ?></dd><?php endif; ?>
			
			<dd class="user_input" id="forum_post_content"><?php echo form::textarea('content', array_key_exists('content', $data) ? html::chars(Security::xss_clean($data['content'])) : ''); ?></dd>
		
		<!-- Submit -->
		<dd class="user_submit" id="forum_post_submit"><button type="submit"><span>Submit</span></button></dd>
	</dl>
	
<?php echo form::close(); ?>