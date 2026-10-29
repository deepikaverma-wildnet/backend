<?php
/**
 * The template for displaying a single Job post.
 *
 * @package PropertyTheme
 */

get_header();
?>

<?php
while ( have_posts() ) :
    the_post();

    // Retrieve custom field values
    $job_title = get_post_meta( get_the_ID(), '_job_title', true );
    $salary    = get_post_meta( get_the_ID(), '_job_salary', true );
    $location  = get_post_meta( get_the_ID(), '_job_location', true );
    ?>

    <!-- Page Header / Hero Section -->
    <section class="bg-dark text-white py-5 mb-5">
        <div class="container text-center py-3">
            <h1 class="entry-title display-4 fw-bold mb-2">
                <?php the_title(); ?>
            </h1>
            <?php if ( ! empty( $job_title ) ) : ?>
                <p class="lead text-primary fw-semibold mb-0">
                    <i class="fa-solid fa-briefcase me-2"></i><?php echo esc_html( $job_title ); ?>
                </p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Main Content Area -->
    <main id="primary" class="site-main pb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">

                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'card border-0 shadow-sm p-4 p-md-5 bg-white' ); ?>>
                        
                        <!-- Job Key Details Bar -->
                        <?php if ( ! empty( $salary ) || ! empty( $location ) ) : ?>
                            <div class="job-meta-details row g-3 bg-light p-3 rounded mb-4 border">
                                <?php if ( ! empty( $location ) ) : ?>
                                    <div class="col-12 col-md-6 d-flex align-items-center">
                                        <i class="fa-solid fa-location-dot text-danger fs-4 me-3"></i>
                                        <div>
                                            <span class="d-block text-muted small fw-bold text-uppercase"><?php esc_html_e( 'Location', 'property-theme' ); ?></span>
                                            <span class="fw-semibold text-dark"><?php echo esc_html( $location ); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ( ! empty( $salary ) ) : ?>
                                    <div class="col-12 col-md-6 d-flex align-items-center">
                                        <i class="fa-solid fa-money-bill-wave text-success fs-4 me-3"></i>
                                        <div>
                                            <span class="d-block text-muted small fw-bold text-uppercase"><?php esc_html_e( 'Salary', 'property-theme' ); ?></span>
                                            <span class="fw-semibold text-dark"><?php echo esc_html( $salary ); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Job Description / Content -->
                        <div class="entry-content text-secondary leading-relaxed fs-6">
                            <h2 class="h4 text-dark fw-bold mb-3"><?php esc_html_e( 'Job Description', 'property-theme' ); ?></h2>
                            <?php the_content(); ?>
                        </div>

                    </article>

                </div>
            </div>
        </div>
    </main>

    <?php
endwhile;

get_footer();