<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package custom_portfolio
 */

get_header();
?>

	<main id="primary" class="site-main">

    <p id="home-explain">Jeremy Wiles-Young is an artist and software engineer working with installation and sound. They are currently based in NYC.</p>

    <?php
    $args = array(
        'post_type'      => 'post',   // Change to custom post type if needed
        'posts_per_page' => 1,        // Only fetch one post
        'orderby'        => 'rand'    // Random order
    );

    $random_post_query = new WP_Query($args);
    if( $random_post_query->have_posts()) : 
      while ($random_post_query->have_posts()) : $random_post_query->the_post(); ?>

        <article class="preview-wrapper">
              <a class="preview-wrapper-link" href="<?php the_permalink(); ?>">
                
                <h2 class="preview-title"><?php the_title(); ?></h2>
                
                
                <?php 
                if (has_post_thumbnail()) {
                    the_post_thumbnail('medium'); // You can specify size: thumbnail, medium, large, full
                } 
                ?>
                <div class="preview-excerpt"><?php the_excerpt(); ?></div>
              </a>
            </article>

        <?php endwhile;
        wp_reset_postdata();
      else :
        echo '<p>No posts found for this category.</p>';
      endif;
    ?>
  </main> 

<?php
get_footer();