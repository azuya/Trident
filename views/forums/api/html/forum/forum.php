<?php echo form::open(NULL, array('id' => 'forum')); ?>

	<dl class="fieldlist">
		<!-- Title -->
		<dt><?php echo form::label('title', 'Title'); ?></dt>
			<?php if (array_key_exists('title', $errors)): ?><dd class="error"><?php echo $errors['title']; ?></dd><?php endif; ?>
			
			<dd class="user_input" id="forum_title"><?php echo form::input('title', array_key_exists('title', $data) ? html::chars(Security::xss_clean($data['title'])) : ''); ?></dd>
		
		<!-- Description -->
		<dt><?php echo form::label('description', 'Description'); ?></dt>
			<?php if (array_key_exists('description', $errors)): ?><dd class="error"><?php echo $errors['description']; ?></dd><?php endif; ?>
			
			<dd class="user_input" id="forum_description"><?php echo form::textarea('description', array_key_exists('description', $data) ? html::chars(Security::xss_clean($data['description'])) : ''); ?></dd>
		
		<!-- Submit -->
		<dd class="user_submit" id="forum_submit"><button type="submit"><span>Submit</span></button></dd>
	</dl>
	
<?php echo form::close(); ?>