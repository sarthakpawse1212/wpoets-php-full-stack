(function ($) {
    'use strict';

    var SliderApp = {
        data: [],
        activeCategoryIndex: 0,
        previewLayer: 'a',
        desktopBreakpoint: 992,

        init: function () {
            this.cache();
            this.bindEvents();
            this.loadData();
        },

        cache: function () {
            this.$app = $('#sliderApp');
            this.$state = $('#stateRegion');
            this.$desktop = $('#desktopExperience');
            this.$mobile = $('#mobileExperience');
            this.$tabs = $('#categoryTabs');
            this.$desktopSlider = $('#desktopSlider');
            this.$desktopEmpty = $('#desktopEmpty');
            this.$activeTitle = $('#activeCategoryTitle');
            this.$preview = $('#imagePreview');
            this.$accordion = $('#categoryAccordion');
            this.apiUrl = this.$app.data('api-url') || 'api/slider-data.php';
        },

        bindEvents: function () {
            var self = this;

            this.$tabs.on('click', '.category-tab', function () {
                self.selectCategory(Number($(this).data('index')));
            });

            this.$desktop.on('keydown', '.slider-panel', function (event) {
                if (!self.$desktopSlider.hasClass('slick-initialized')) {
                    return;
                }

                if (event.key === 'ArrowLeft') {
                    event.preventDefault();
                    self.$desktopSlider.slick('slickPrev');
                }

                if (event.key === 'ArrowRight') {
                    event.preventDefault();
                    self.$desktopSlider.slick('slickNext');
                }
            });

            this.$accordion.on('shown.bs.collapse', '.accordion-collapse', function () {
                var $slider = $(this).find('.mobile-slider');
                self.ensureMobileSlider($slider);
                $slider.slick('setPosition');
            });

            $(window).on('resize', this.debounce(function () {
                self.refreshVisibleSliders();
            }, 150));
        },

        loadData: function () {
            var self = this;
            this.showLoading();

            $.ajax({
                url: this.apiUrl,
                method: 'GET',
                dataType: 'json',
                cache: false
            }).done(function (response) {
                self.data = self.normalizeData(response);

                if (!self.data.length) {
                    self.showEmpty('No slider content is available yet.');
                    return;
                }

                self.render();
            }).fail(function () {
                self.showError('Unable to load slider content. Please try again later.');
            });
        },

        normalizeData: function (items) {
            if (!Array.isArray(items)) {
                return [];
            }

            return items
                .filter(function (item) {
                    return item && item.category;
                })
                .map(function (item) {
                    return {
                        category: item.category,
                        slides: Array.isArray(item.slides) ? item.slides : []
                    };
                });
        },

        render: function () {
            this.$state.empty();
            this.$desktop.removeClass('d-none');
            this.$mobile.removeClass('d-none');
            this.renderTabs();
            this.renderAccordion();
            this.selectCategory(0);
            this.openFirstAccordion();
        },

        renderTabs: function () {
            var self = this;
            var html = this.data.map(function (item, index) {
                return [
                    '<button type="button" class="nav-link category-tab" role="tab"',
                    ' aria-selected="false"',
                    ' data-index="' + index + '">',
                    self.escapeHtml(item.category.name || 'Untitled Category'),
                    '</button>'
                ].join('');
            }).join('');

            this.$tabs.html(html);
        },

        renderAccordion: function () {
            var self = this;
            var html = this.data.map(function (item, index) {
                var headingId = 'categoryHeading' + index;
                var collapseId = 'categoryCollapse' + index;
                var isFirst = index === 0;

                return [
                    '<div class="accordion-item">',
                    '<h2 class="accordion-header" id="' + headingId + '">',
                    '<button class="accordion-button' + (isFirst ? '' : ' collapsed') + '" type="button"',
                    ' data-bs-toggle="collapse"',
                    ' data-bs-target="#' + collapseId + '"',
                    ' aria-expanded="' + (isFirst ? 'true' : 'false') + '"',
                    ' aria-controls="' + collapseId + '">',
                    self.escapeHtml(item.category.name || 'Untitled Category'),
                    '</button>',
                    '</h2>',
                    '<div id="' + collapseId + '" class="accordion-collapse collapse' + (isFirst ? ' show' : '') + '"',
                    ' aria-labelledby="' + headingId + '" data-bs-parent="#categoryAccordion">',
                    '<div class="accordion-body">',
                    self.renderSliderMarkup(item.slides, 'mobile', index),
                    '</div>',
                    '</div>',
                    '</div>'
                ].join('');
            }).join('');

            this.$accordion.html(html);
        },

        renderSliderMarkup: function (slides, mode, categoryIndex) {
            var self = this;

            if (!slides.length) {
                return '<div class="inline-empty">No slides found for this category.</div>';
            }

            var classes = mode === 'mobile' ? 'content-slider mobile-slider' : 'content-slider desktop-slider';
            var cards = slides.map(function (slide, slideIndex) {
                var image = self.imageUrl(slide.image);
                var bgAttr = mode === 'mobile' && image ? ' data-bg="' + self.escapeAttr(image) + '"' : '';

                return [
                    '<article class="slide-card" data-image="' + self.escapeAttr(image) + '"' + bgAttr + '>',
                    '<span class="slide-meta">Slide ' + (slideIndex + 1) + '</span>',
                    '<h3>' + self.escapeHtml(slide.title || 'Untitled Slide') + '</h3>',
                    '<p>' + self.escapeHtml(slide.description || '') + '</p>',
                    '</article>'
                ].join('');
            }).join('');

            return '<div class="' + classes + '" data-category-index="' + categoryIndex + '">' + cards + '</div>';
        },

        selectCategory: function (index) {
            var item = this.data[index];

            if (!item) {
                return;
            }

            this.activeCategoryIndex = index;
            this.$tabs.find('.category-tab')
                .removeClass('active')
                .attr('aria-selected', 'false')
                .eq(index)
                .addClass('active')
                .attr('aria-selected', 'true');

            this.$activeTitle.text(item.category.name || 'Untitled Category');
            this.destroySlider(this.$desktopSlider);

            if (!item.slides.length) {
                this.$desktopSlider.empty();
                this.$desktopEmpty.removeClass('d-none');
                this.setPreview('');
                return;
            }

            this.$desktopEmpty.addClass('d-none');
            this.$desktopSlider.replaceWith($(this.renderSliderMarkup(item.slides, 'desktop', index)));
            this.$desktopSlider = $('#desktopExperience .desktop-slider');
            this.initDesktopSlider();
            this.setPreview(this.imageUrl(item.slides[0].image));
        },

        initDesktopSlider: function () {
            var self = this;

            this.$desktopSlider
                .on('beforeChange', function (event, slick, currentSlide, nextSlide) {
                    var slide = self.data[self.activeCategoryIndex].slides[nextSlide];
                    self.setPreview(self.imageUrl(slide ? slide.image : ''));
                })
                .slick(this.sliderOptions(true));
        },

        ensureMobileSlider: function ($slider) {
            if (!$slider.length || $slider.hasClass('slick-initialized')) {
                return;
            }

            this.applyMobileLazyBackgrounds($slider, 0);

            $slider
                .on('beforeChange', function (event, slick, currentSlide, nextSlide) {
                    SliderApp.applyMobileLazyBackgrounds($slider, nextSlide);
                })
                .slick(this.sliderOptions(false));
        },

        applyMobileLazyBackgrounds: function ($slider, index) {
            var indexes = [index, index + 1, index - 1];

            indexes.forEach(function (slideIndex) {
                var $card = $slider.find('[data-slick-index="' + slideIndex + '"] .slide-card');

                if (!$card.length) {
                    $card = $slider.find('.slide-card').eq(slideIndex);
                }

                var bg = $card.data('bg');

                if (bg && !$card.data('bgLoaded')) {
                    $card.css('background-image', 'url("' + String(bg).replace(/"/g, '\\"') + '")');
                    $card.data('bgLoaded', true);
                }
            });
        },

        openFirstAccordion: function () {
            var $firstSlider = this.$accordion.find('.accordion-collapse.show .mobile-slider').first();
            this.ensureMobileSlider($firstSlider);
        },

        refreshVisibleSliders: function () {
            if (this.$desktopSlider.hasClass('slick-initialized')) {
                this.$desktopSlider.slick('setPosition');
            }

            this.$accordion.find('.accordion-collapse.show .mobile-slider').each(function () {
                var $slider = $(this);

                if ($slider.hasClass('slick-initialized')) {
                    $slider.slick('setPosition');
                }
            });
        },

        sliderOptions: function (dots) {
            return {
                arrows: true,
                dots: dots,
                infinite: false,
                adaptiveHeight: true,
                speed: 360,
                cssEase: 'ease',
                swipe: true,
                touchThreshold: 12,
                accessibility: true,
                prevArrow: '<button type="button" class="slick-prev" aria-label="Previous slide">Previous</button>',
                nextArrow: '<button type="button" class="slick-next" aria-label="Next slide">Next</button>'
            };
        },

        setPreview: function (image) {
            var self = this;

            if (!image) {
                this.$preview
                    .addClass('is-empty')
                    .removeClass('layer-a layer-b')
                    .removeAttr('style');
                return;
            }

            $('<img>', { src: image }).on('load error', function () {
                var nextLayer = self.previewLayer === 'a' ? 'b' : 'a';
                self.$preview
                    .removeClass('is-empty')
                    .css(nextLayer === 'a' ? '--preview-a' : '--preview-b', 'url("' + image.replace(/"/g, '\\"') + '")')
                    .toggleClass('layer-a', nextLayer === 'a')
                    .toggleClass('layer-b', nextLayer === 'b');

                self.previewLayer = nextLayer;
            });
        },

        destroySlider: function ($slider) {
            if ($slider.length && $slider.hasClass('slick-initialized')) {
                $slider.slick('unslick');
            }
        },

        showLoading: function () {
            this.$state.html([
                '<div class="loading-state">',
                '<span class="loader" aria-hidden="true"></span>',
                '<span>Loading slider content...</span>',
                '</div>'
            ].join(''));
        },

        showEmpty: function (message) {
            this.$desktop.addClass('d-none');
            this.$mobile.addClass('d-none');
            this.$state.html('<div class="empty-state">' + this.escapeHtml(message) + '</div>');
        },

        showError: function (message) {
            this.$desktop.addClass('d-none');
            this.$mobile.addClass('d-none');
            this.$state.html('<div class="error-state">' + this.escapeHtml(message) + '</div>');
        },

        imageUrl: function (path) {
            if (!path) {
                return '';
            }

            if (/^(https?:)?\/\//i.test(path) || path.indexOf('data:') === 0) {
                return path;
            }

            return path.replace(/^\/+/, '');
        },

        cssUrl: function (url) {
            return '"' + String(url).replace(/"/g, '\\"') + '"';
        },

        escapeHtml: function (value) {
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        },

        escapeAttr: function (value) {
            return this.escapeHtml(value);
        },

        debounce: function (callback, wait) {
            var timeout;

            return function () {
                var context = this;
                var args = arguments;

                clearTimeout(timeout);
                timeout = setTimeout(function () {
                    callback.apply(context, args);
                }, wait);
            };
        }
    };

    $(function () {
        SliderApp.init();
    });
})(jQuery);
