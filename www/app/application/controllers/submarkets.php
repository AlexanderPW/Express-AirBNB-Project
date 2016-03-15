<?php
/*
Template Name: Backbone Page
*/
if (!function_exists('wp') && !empty($_SERVER['SCRIPT_FILENAME']) && basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    die ('You do not have sufficient permissions to access this page!');
}
get_header();
?>
    <div class="body">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <div class="main">
                    <div class="content">
                        <div id="backbone-container"></div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
        <div class="clearfix"></div>

    </div>

<?php get_footer(); ?>