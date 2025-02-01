<?php
/* Template Name: CV Page */
get_header(); ?>

<main class="cv-page-wrapper">

    <?php
    // Fetch all CV categories (e.g., Exhibitions, Residencies, etc.)
    $cv_categories = get_terms(array(
        'taxonomy'   => 'cv_category', // Adjust to your taxonomy name
        'hide_empty' => true,
    ));

    if (!empty($cv_categories)) :
        foreach ($cv_categories as $category) :
    ?>

    <h2 class="wp-block-heading"><?php echo esc_html($category->name); ?></h2>
    <div class="list-line"></div>
    
    <ul class="cv-list-wrapper">
        <?php
        // Fetch posts within this category
        $cv_query = new WP_Query(array(
            'post_type'      => 'cv_entries',
            'posts_per_page' => -1,
            'orderby'        => 'meta_value',
            'meta_key'       => 'date', // Assuming ACF date field is named 'date'
            'order'          => 'DESC',
            'tax_query'      => array(
                array(
                    'taxonomy' => 'cv_category',
                    'field'    => 'term_id',
                    'terms'    => $category->term_id,
                ),
            ),
        ));

        if ($cv_query->have_posts()) :
            while ($cv_query->have_posts()) : $cv_query->the_post();
        ?>
            <li>
                <span class="cv-date"><?php the_field('date'); ?>:</span>
                <span class="cv-list-name"><?php the_title(); ?></span>.
                <?php the_field('location'); ?>
            </li>
        <?php 
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </ul>

    <?php 
        endforeach;
    else :
        echo '<p>No CV entries found.</p>';
    endif;
    ?>

</main>

<?php get_footer(); ?>
