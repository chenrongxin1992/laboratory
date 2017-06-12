<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Catch Themes
 * @subpackage Catch Kathmandu
 * @since Catch Kathmandu 1.0
 */
?>

<?php
/**
 * catchkathmandu_above_secondary hook
 */
do_action( 'catchkathmandu_before_secondary' );

$layout = catchkathmandu_get_theme_layout();

if ( 'left-sidebar' == $layout  || 'right-sidebar' == $layout  ) { ?>
	<div id="secondary" class="widget-area" role="complementary">
		<?php
		/**
		 * catchkathmandu_before_widget_start hook
		 */
		do_action( 'catchkathmandu_before_widget_start' );

		if ( is_active_sidebar( 'sidebar-1' ) ) {
        	dynamic_sidebar( 'sidebar-1' );
   		}
		else { ?>
			<aside id="search" class="widget widget_search">
				<?php get_search_form(); ?>
			</aside>

			<aside id="archives" class="widget">
				<h1 class="widget-title"><?php _e( 'Archives', 'catch-kathmandu' ); ?></h1>
				<ul>
					<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
				</ul>
			</aside>
		<?php
		} // end sidebar widget area

		/**
		 * catchkathmandu_after_widget_ends hook
		 */
		do_action( 'catchkathmandu_after_widget_ends' ); ?>
	</div><!-- #secondary .widget-area -->

	<?php
}

/**
 * catchkathmandu_after_secondary hook
 */
do_action( 'catchkathmandu_after_secondary' );