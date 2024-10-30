<?php 
$uid = 'c4d-woo-cate-carousel-'.uniqid();
?>
<script>
	(function($){
		$(document).ready(function(){
			c4dWooCateCarousel['<?php echo $uid; ?>'] = <?php echo json_encode($params); ?>;
		});	
	})(jQuery);
</script>
<div class="c4d-woo-cate-carousel">
	<div class="c4d-woo-cate-carousel__slider">
		<div id="<?php echo esc_attr($uid); ?>">
			<?php foreach ($items as $key => $item): ?>
				<div class="item">
					<a href="<?php echo esc_url($item->permalink); ?>">
						<div class="icon"><?php echo $item->content; ?></div>
						<h3 class="title"><?php echo $item->title; ?></h3>
						<div class="count">
							<?php echo sprintf( _n( '%s item', '%s items', $item->count, 'c4d-woo-cate-carousel' ), $item->count ); ?>
						</div>
					</a>
				</div>		
			<?php endforeach; ?>
		</div>
	</div>
</div>