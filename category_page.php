<?php
/**
 * Template Name: Category Page
 */

get_header();

$page_slug = get_post_field( 'post_name', get_the_ID() ); // Get the page slug

// Define a mapping table for pages that don't match category names exactly
$category_map = array(
  'sounds' => 'sound',
  'videos' => 'video',
  'webportfolio' => 'webportfolio',
  'things' => 'things'
  // Add more mappings as needed
);

// Use the mapped category slug if it exists, otherwise use the page slug
$category_slug = $category_map[$page_slug];

// Display the slug as a title
echo '<h1 class="category-page-title">' . ucfirst(str_replace('-', ' ', $page_slug)) . '</h1>';

// Get the category object
$category = get_category_by_slug($category_slug);

if ($category) {
    $args = array(
        'category_name' => $category->slug,
        'posts_per_page' => 10, 
        'meta_key'       => 'date',
        'orderby'        => 'meta_value',
        'order'          => 'DESC', // DESC latest first
        'meta_type'      => 'DATE' // Ensures proper date sorting
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) : 
        while ($query->have_posts()) : $query->the_post(); ?>

            <article class="preview-wrapper">
              <a class="preview-wrapper-link" href="<?php the_permalink(); ?>">
                
                <h2 class="preview-title"><?php the_title(); ?></h2>
                
                
                <?php 
                if (has_post_thumbnail()) {
                    the_post_thumbnail('large'); // You can specify size: thumbnail, medium, large, full
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
} else {
    echo '<p>No matching category found for this page.</p>';
}

get_footer();
