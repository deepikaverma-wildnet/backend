<?php
/**
 * The template for displaying Job Archives.
 *
 * @package PropertyTheme
 */

get_header();
?>

<!-- Page Header / Hero Section -->
<section class="bg-dark text-white py-5 mb-5">
    <div class="container text-center py-3">
        <h1 class="page-title display-4 fw-bold mb-2">
            <?php post_type_archive_title(); ?>
        </h1>
        <p class="lead text-secondary mb-0">
            <?php esc_html_e( 'Browse our latest available career opportunities and open positions.', 'property-theme' ); ?>
        </p>
    </div>
</section>

<!-- Main Content Area -->
<main id="primary" class="site-main pb-5">
    <div class="container">

        <?php if ( have_posts() ) : ?>

            <!-- 3-Column Jobs Grid -->
            <div class="row g-4 mb-5">
                <?php
                while ( have_posts() ) :
                    the_post();

                    // Retrieve custom field values
                    $job_title = get_post_meta( get_the_ID(), '_job_title', true );
                    $salary    = get_post_meta( get_the_ID(), '_job_salary', true );
                    $location  = get_post_meta( get_the_ID(), '_job_location', true );
                    ?>

                    <div class="col-12 col-md-6 col-lg-4">
                        <article id="post-<?php the_ID(); ?>" <?php post_class( 'card h-100 border-0 shadow-sm property-card bg-white' ); ?>>
                            
                            <div class="card-body d-flex flex-column p-4">
                                
                                <!-- Job Role / Title Badge -->
                                <?php if ( ! empty( $job_title ) ) : ?>
                                    <span class="badge bg-primary-subtle text-primary fw-semibold align-self-start mb-2 px-2 py-1 fs-7">
                                        <i class="fa-solid fa-briefcase me-1"></i><?php echo esc_html( $job_title ); ?>
                                    </span>
                                <?php endif; ?>

                                <!-- Main Post Title -->
                                <h2 class="card-title h5 mb-3">
                                    <a href="<?php the_permalink(); ?>" class="text-decoration-none text-dark fw-bold">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>

                                <!-- Job Location -->
                                <?php if ( ! empty( $location ) ) : ?>
                                    <p class="text-muted small mb-3">
                                        <i class="fa-solid fa-location-dot me-1 text-danger"></i><?php echo esc_html( $location ); ?>
                                    </p>
                                <?php endif; ?>

                                <!-- Job Excerpt Summary -->
                                <div class="entry-summary text-secondary small mb-4">
                                    <?php the_excerpt(); ?>
                                </div>

                                <!-- Card Footer Metadata & Link -->
                                <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-success small">
                                        <?php if ( ! empty( $salary ) ) : ?>
                                            <i class="fa-solid fa-money-bill-wave me-1"></i><?php echo esc_html( $salary ); ?>
                                        <?php else : ?>
                                            <?php esc_html_e( 'Competitive', 'property-theme' ); ?>
                                        <?php endif; ?>
                                    </span>

                                    <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary btn-sm">
                                        <?php esc_html_e( 'View Details', 'property-theme' ); ?> <i class="fa-solid fa-arrow-right ms-1"></i>
                                    </a>
                                </div>

                            </div>

                        </article>
                    </div>

                <?php endwhile; ?>
            </div>

        <?php else : ?>

            <div class="alert alert-info text-center py-4 shadow-sm" role="alert">
                <i class="fa-solid fa-circle-info fs-4 me-2 align-middle"></i>
                <span class="align-middle"><?php esc_html_e( 'No jobs found.', 'property-theme' ); ?></span>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php
get_footer();