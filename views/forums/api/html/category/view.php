<div class="category" id="forum_category_<?php echo $category->id; ?>">
	<?php echo $actions; ?>
	
	<h2><?php echo html::chars(Security::xss_clean($category->title)); ?></h2>
	
	<ul class="standard_list forums">
		<?php foreach($forums as $forum): ?>
		
		<?php if ((String) $forum !== ''): ?>
		
		<li class="standard_list_item forum_list_item">
			<?php echo $forum; ?>
			
		</li>
		<?php endif; ?>
		
		<?php endforeach; ?>
	</ul>
	
</div>