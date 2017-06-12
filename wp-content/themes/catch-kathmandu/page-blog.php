<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Blog Template
 *
   Template Name: Blog
 *
 * The template for Blog
 *
 * @package Catch Themes
 * @subpackage Catch Kathmandu
 * @since Catch Kathmandu 1.0
 */

get_header(); 

global $catchkathmandu_options_settings; 
//Getting data from Theme Options Panel and Meta Box  
$options = $catchkathmandu_options_settings;

//Content Layout
$current_content_layout = $options['content_layout'];

//More Tag
$moretag = $options['more_tag_text'];
?>

		<div id="primary" class="content-area">
			<div id="content" class="site-content" role="main">
            
            	<?php 
				$paged = intval( ( get_query_var('paged') ) ? get_query_var('paged') : 1 );
				
				$blog_query = new WP_Query( array( 'post_type' => 'post', 'paged' => $paged ) );

				if ( $blog_query->have_posts() ) : ?>
                
                	<header class="page-header">
						<h1 class="page-title"><?php the_title(); ?></h1>
                  	</header><!-- .page-header -->
                    
                    <?php 
                    // Pagination start
                    if ( $blog_query->max_num_pages > 1 ) { ?>
                        <nav role="navigation" id="nav-above" class="site-navigation paging-navigation">
                            <h3 class="assistive-text"><?php esc_html_e( 'Post navigation', 'catch-kathmandu' ); ?></h3>
                            
                            <?php 
                            if ( function_exists('wp_pagenavi' ) )  {
                                wp_pagenavi();
                            } elseif ( function_exists('wp_page_numbers' ) ) {
                                wp_page_numbers();
                            } else { ?>
                                <div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'catch-kathmandu' ), $blog_query->max_num_pages ); ?></div>
                                <div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'catch-kathmandu' ) ); ?></div>
                            <?php
                            } ?>
                        </nav><!-- #nav -->
                    <?php
                    }
                    ?>
                    
					<?php /* Start the Loop */ ?>
					<?php while ( $blog_query->have_posts() ) : $blog_query->the_post(); ?>
                    
						<?php
                            /* Include the Post-Format-specific template for the content.
                             * If you want to overload this in a child theme then include a file
                             * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                             */
                            get_template_part( 'content', get_post_format() );
                        ?>

					<?php endwhile; ?>
                        
                    <?php 
                    // Pagination start
                    if ( $blog_query->max_num_pages > 1 ) { ?>
                        <nav role="navigation" id="nav-below" class="site-navigation paging-navigation">
                            <h3 class="assistive-text"><?php esc_html_e( 'Post navigation', 'catch-kathmandu' ); ?></h3>
                            
                            <?php 
                            if ( function_exists('wp_pagenavi' ) )  {
                                wp_pagenavi();
                            } elseif ( function_exists('wp_page_numbers' ) ) {
                                wp_page_numbers();
                            } else { ?>
                                <div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'catch-kathmandu' ), $blog_query->max_num_pages ); ?></div>
                                <div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'catch-kathmandu' ) ); ?></div>
                            <?php
                            } ?>
                        </nav><!-- #nav -->
                    <?php
                    }
                    ?>

				<?php else : ?>   

					<?php get_template_part( 'no-results', 'archive' ); ?>
					
				<?php endif; 
				wp_reset_postdata();
				?>

			</div><!-- #content .site-content -->
		</div><!-- #primary .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>