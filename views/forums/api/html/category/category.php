<?php echo form::open(NULL, array('id' => 'category')); ?>

	<dl class="fieldlist">
		<!-- Title -->
		<dt><?php echo form::label('title', 'Title'); ?></dt>
			<?php if (array_key_exists('title', $errors)): ?><dd class="error"><?php echo $errors['title']; ?></dd><?php endif; ?>
			
			<dd class="user_input" id="category_title"><?php echo form::input('title', array_key_exists('title', $data) ? html::chars(Security::xss_clean($data['title'])) : ''); ?></dd>
		
		<!-- Submit -->
		<dd class="user_submit" id="category_submit"><button type="submit"><span>Submit</span></button></dd>
	</dl>
	
<?php echo form::close(); ?>