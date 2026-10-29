<?php
/**
 * The template for displaying all single pages.
 *
 * @package PropertyTheme
 */

get_header();
?>

<main id="primary" class="site-main py-5">
    <div class="container">
        <?php
        while ( have_posts() ) :
            the_post();
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class( 'bg-white p-4 p-md-5 rounded shadow-sm' ); ?>>
                
                <!-- Page Title -->
                <header class="entry-header mb-4 border-bottom pb-3">
                    <h1 class="entry-title display-5 fw-bold"><?php the_title(); ?></h1>
                </header>

                <!-- Page Content -->
                <div class="entry-content text-secondary leading-relaxed">
                    <?php
                    the_content();
                    ?>
                </div>

            </article>

            <?php
        endwhile;
        ?>
    </div>
</main>

<?php
get_footer();