<?php
			$args = array(
				'posts_per_page' => 5,
				'orderby' => date,
				'post__in'  => get_option('sticky_posts'),
				'ignore_sticky_posts' => 10
			);
			query_posts($args);
			?>
			
	<div id="slideshow">	
<ul id="slider">
	<?php if (have_posts()) : ?>
		
			<?php while (have_posts()) : the_post(); ?>
		<li>
			<a href="<?php the_permalink() ?>" target="_blank" rel="bookmark">
				<?php if ( get_post_meta($post->ID, 'show', true) ) : ?>
				<?php $image = get_post_meta($post->ID, 'show', true); ?>
				<img src="<?php echo $image; ?>" alt="<?php the_title(); ?>">
				<?php else: ?>
				<img src="<?php $random = mt_rand(1, 5);
		echo get_bloginfo ( 'stylesheet_directory' );
		echo '/images/hdp/'.$random.'.jpg'; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>">
				<?php endif; ?>
			</a>
			<div class="slider-caption"><p><?php echo cut_str($post->post_title,50); ?></p></div>
		</li>
		<?php endwhile; ?>
		<?php endif; ?>	
	</ul>	
	</div>	