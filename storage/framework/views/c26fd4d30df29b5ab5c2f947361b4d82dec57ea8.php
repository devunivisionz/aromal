     
		
<?php echo $__env->make('common.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="page-container">
    
<?php echo $__env->make('common.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class='page-content-wrapper'>
<div class='page-content'>
	<div class="content-wrapper">
       <?php echo $__env->yieldContent('content'); ?>
   </div>
</div>
</div>
	   </div>
	   
<?php echo $__env->make('common.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
   <?php echo $__env->yieldContent('footer-scripts'); ?>
         
	<?php /**PATH /var/www/vhosts/db-preprod.com360degree.com/httpdocs/resources/views/layouts/pagelayout.blade.php ENDPATH**/ ?>