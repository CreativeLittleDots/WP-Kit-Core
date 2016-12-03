<div class="footer-notification" <?php echo wc_notice_count() ? 'style="display: block;"' : 'style="display: none;"'; ?>>
		
	<div class="row">
	
		<div class="columns padding">
	
			<?php wc_print_notices(); ?>
			
		</div>
		
	</div>
	
</div>