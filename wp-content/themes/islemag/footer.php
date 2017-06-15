<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package islemag
 */

?>

		</div><!-- #content -->

		<footer id="footer" class="footer-inverse" role="contentinfo">
			<div id="footer-inner">
				<div class="container">
					<div class="row">

						

						<?php if ( is_active_sidebar( 'islemag-first-footer-area' ) ) {  ?>
								<div itemscope itemtype="http://schema.org/WPSideBar" class="col-md-4 col-sm-12" id="sidebar-widgets-area-1" aria-label="<?php esc_html_e( 'Widgets Area 1','islemag' ); ?>">
									<?php dynamic_sidebar( 'islemag-first-footer-area' ); ?>
								</div>
						<?php }

if ( is_active_sidebar( 'islemag-second-footer-area' ) ) {  ?>
								<div itemscope itemtype="http://schema.org/WPSideBar" role="complementary" id="sidebar-widgets-area-2" class="col-md-4 col-sm-12" aria-label="<?php esc_html_e( 'Widgets Area 2','islemag' ); ?>">
									<?php dynamic_sidebar( 'islemag-second-footer-area' ); ?>
								</div>
						<?php }

if ( is_active_sidebar( 'islemag-third-footer-area' ) ) {  ?>
								<div itemscope itemtype="http://schema.org/WPSideBar" role="complementary" id="sidebar-widgets-area-3" class="col-md-4 col-sm-12" aria-label="<?php esc_html_e( 'Widgets Area 3','islemag' ); ?>">
									<?php dynamic_sidebar( 'islemag-third-footer-area' ); ?>
								</div>
						<?php
}
						?>

					</div><!-- End .row -->
				</div><!-- End .container -->
			</div><!-- End #footer-inner -->
			<div id="footer-bottom" class="no-bg">
				<div class="islemag-footer-container">
					<?php
					islemag_footer_container_head();

					islemag_footer_content();

	                islemag_footer_container_bottom();?>

				</div><!-- End .row -->
			</div><!-- End #footer-bottom -->
		</footer><!-- End #footer -->
	</div><!-- #page -->
</div><!-- End #wrapper -->
<?php wp_footer(); ?>

</body>
</html>
