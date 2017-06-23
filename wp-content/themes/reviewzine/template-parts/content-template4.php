<?php
/**
 * Fourth template
 *
 * @package reviewzine
 */

$wp_query = new WP_Query(
	array(
		'posts_per_page'      => $islemag_section_max_posts,
		'order'               => 'DESC',
		'no_found_rows'       => true,
		'ignore_sticky_posts' => true,
		'post_status'         => 'publish',
		'category_name' 	    => ( ! empty( $islemag_section_category ) && $islemag_section_category != 'all' ? $islemag_section_category : ''),
		)
);

if ( $wp_query->have_posts() ) :
?>
<div class="post-section islemag-template4 mb30">
	<ul class="article-list">
		<?php
		  $counter = 0;

		while ( $wp_query->have_posts() ) : $wp_query->the_post();
			$case = $counter % 2;
			$category = get_the_category();
			$postid = get_the_ID();
		?>
		<li>
			
			<span class="post-title">
	            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" target="_blank"><?php the_title(); ?></a>
	        </span>
	        <!-- 显示发布日期 -->
	            <span class="post-date"><i class="fa fa-calendar-o" style="padding-right: 2px;color: #8d8d8d;"></i><?php echo esc_html( get_the_date() ); ?></span>
        </li>
		<?php  endwhile;?>

	</ul>
</div> <!-- End .post-section -->
<?php endif;
wp_reset_postdata(); ?>
