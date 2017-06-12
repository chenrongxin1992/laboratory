<?php
/**
 * The template for displaying posts in the Status post format
 *
 * @package Catch Themes
 * @subpackage Catch Kathmandu
 * @since Catch Kathmandu 1.0
 */
//Getting data from Theme Options Panel and Meta Box 
global $catchkathmandu_options_settings;
$options = $catchkathmandu_options_settings; 

//More Tag
$moretag = $options['more_tag_text'];
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <?php if ( function_exists( 'catchkathmandu_content_image' ) ) : catchkathmandu_content_image(); endif; ?>
    
	<div class="entry-container post-format">
    
		<div class="entry-header">
            <header>
                <?php echo get_avatar( get_the_author_meta( 'ID' ), apply_filters( 'catchkathmandu_status_avatar', '60' ) ); ?>
                <h1 class="entry-title"><?php the_author(); ?></h1>
            </header>
            <h2 class="entry-format"><a href="<?php echo esc_url( get_post_format_link( 'status' ) ); ?>" title="<?php esc_attr_e( 'All Status Posts', 'catch-kathmandu' ); ?>"><?php esc_attr_e( 'Status', 'catch-kathmandu' ); ?></a></h2>
		</div><!-- .entry-header -->

		<div class="entry-content">
			<?php the_content( $moretag ); ?>
			<?php wp_link_pages( array( 
                'before'		=> '<div class="page-link"><span class="pages">' . __( 'Pages:', 'catch-kathmandu' ) . '</span>',
                'after'			=> '</div>',
                'link_before' 	=> '<span>',
                'link_after'   	=> '</span>',
            ) ); 
            ?>
		</div><!-- .entry-content -->

        <footer class="entry-meta">
            <?php catchkathmandu_post_format_meta(); ?>   
            <?php if ( comments_open() ) : ?>
            	<span class="sep"> | </span>
            	<span class="comments-link"><?php comments_popup_link(__('Leave a reply', 'catch-kathmandu'), __('1 Reply', 'catch-kathmandu'), __('% Replies;', 'catch-kathmandu')); ?></span>
            <?php endif; ?>
            <?php edit_post_link( __( 'Edit', 'catch-kathmandu' ), '<span class="sep"> | </span><span class="edit-link">', '</span>' ); ?>
        </footer><!-- .entry-meta -->
        
   	</div><!-- .entry-container -->
         
</article><!-- #post-<?php the_ID(); ?> -->