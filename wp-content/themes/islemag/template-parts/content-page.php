<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package islemag
 */

?>
<p style="font-family:Lato;font-weight: bold;padding: 0 4px;">  当前位置&nbsp;:&nbsp; <a href="<?php bloginfo('url');?>">
<i class="fa fa-home" aria-hidden="true"></i>首页</a>
>>
<?php the_title()?>  </p>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<p style="font-family:Lato;font-weight: normal;text-align: center;">  发布时间：
				<?php the_time('Y-n-j')?>  </p>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'islemag' ),
				'after'  => '</div>',
			) );
		?>
		<?php if(function_exists('the_views')) { the_views(true, '<span class="pull-right">','</span>'); } ?>
	</div><!-- .entry-content -->

	
	<?php
		edit_post_link(
			sprintf(
				/* translators: %s: Name of current post */
				esc_html__( 'Edit %s', 'islemag' ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			),
			'<footer class="entry-footer"><span class="edit-link">',
			'</span></footer>'
		);
	?>

</article><!-- #post-## -->

