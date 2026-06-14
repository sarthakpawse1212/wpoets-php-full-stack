<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WPoets Slider Assessment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<a class="visually-hidden-focusable skip-link" href="#sliderApp">Skip to slider content</a>

<header class="site-header">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between gap-3">
            <a href="index.php" class="brand-mark" aria-label="WPoets slider home">
                <span class="brand-symbol">W</span>
                <span class="brand-copy">
                    <span class="brand-title">WPoets</span>
                    <span class="brand-subtitle">Full Stack Assessment</span>
                </span>
            </a>
            <a href="admin/categories/index.php" class="btn btn-outline-dark btn-sm">Admin</a>
        </div>
    </div>
</header>

<main id="sliderApp" class="slider-app" data-api-url="api/slider-data.php">
    <section class="intro-section">
        <div class="container">
            <div class="intro-copy">
                <p class="eyebrow mb-2">Category synced content slider</p>
                <h1>Explore curated technologies by category.</h1>
                <p class="lead mb-0">Tabs, slides, and preview images stay synchronized from the backend API.</p>
            </div>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <div id="stateRegion" class="state-region" role="status" aria-live="polite">
                <div class="loading-state">
                    <span class="loader" aria-hidden="true"></span>
                    <span>Loading slider content...</span>
                </div>
            </div>

            <div id="desktopExperience" class="desktop-experience d-none" aria-label="Desktop category slider">
                <div class="row g-4 align-items-stretch">
                    <div class="col-lg-3">
                        <nav class="category-tabs" aria-label="Slider categories">
                            <div id="categoryTabs" class="nav flex-column nav-pills" role="tablist"></div>
                        </nav>
                    </div>

                    <div class="col-lg-5">
                        <section class="slider-panel" aria-label="Selected category slides" tabindex="0">
                            <div class="panel-heading">
                                <p class="eyebrow mb-1">Selected Category</p>
                                <h2 id="activeCategoryTitle" class="h4 mb-0"></h2>
                            </div>
                            <div id="desktopSlider" class="content-slider"></div>
                            <div id="desktopEmpty" class="inline-empty d-none">No slides found for this category.</div>
                        </section>
                    </div>

                    <div class="col-lg-4">
                        <aside class="image-preview-panel" aria-label="Slide image preview">
                            <div id="imagePreview" class="image-preview is-empty">
                                <span class="preview-placeholder">Image preview</span>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>

            <div id="mobileExperience" class="mobile-experience d-none">
                <div class="accordion slider-accordion" id="categoryAccordion"></div>
            </div>
        </div>
    </section>
</main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
