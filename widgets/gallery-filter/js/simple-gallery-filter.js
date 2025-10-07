(function ($) {
    "use strict";

    // Initialize filterable gallery + popup
    const initEelFilterableGallery = function ($scope) {
        const $gallery = $scope.find('.eel-gallery-filter');
        if (!$gallery.length) return;

        // Grid-level preloader
        var $gridLoader = $gallery.find('.eel-grid-loader');
        if (!$gridLoader.length) {
            $gridLoader = $('<div class="eel-grid-loader is-active" aria-hidden="true"></div>');
            $gallery.append($gridLoader);
        } else {
            $gridLoader.addClass('is-active');
        }

        // Wait until all images are loaded
        var $gridImages = $gallery.find('img');
        var total = $gridImages.length, loaded = 0;
        function checkDone() {
            if (loaded >= total) $gridLoader.removeClass('is-active');
        }
        $gridImages.each(function () {
            var img = this;
            if (img.complete && img.naturalWidth) {
                loaded++; checkDone();
            } else {
                $(img).one('load error', function () {
                    loaded++; checkDone();
                });
            }
        });

        // Detect Elementor edit mode
        var isEditMode = (typeof elementorFrontend !== 'undefined' && elementorFrontend &&
                          typeof elementorFrontend.isEditMode === 'function' && elementorFrontend.isEditMode());

        if (isEditMode) {
            setTimeout(function () { $gridLoader.removeClass('is-active'); }, 150);
        }

        // Isotope initialization
        var isoInstance = null;
        var usingIsotope = typeof Isotope !== 'undefined' && $gallery.hasClass('eel-uses-isotope');

        function applyIsotopeItemWidths() {
            if (!usingIsotope) return;
            try {
                var styles = getComputedStyle($gallery[0]);
                var gap = styles.gap || styles.gridGap || '15px';
                var colsDef = styles.gridTemplateColumns || '';
                var cols = colsDef ? colsDef.split(' ').length : 0;
                if (!cols || cols < 1) cols = 4;
                var widthPercent = (100 / cols) + '%';
                $gallery[0].style.setProperty('--eel-gap', gap);
                $gallery.find('.eel-gallery-item').css('width', widthPercent);
            } catch (e) {}
        }

        if (usingIsotope) {
            try {
                if (typeof imagesLoaded === 'function') {
                    imagesLoaded($gallery[0]).on('progress', function () {
                        if (isoInstance) isoInstance.layout();
                    }).on('always', function () {
                        $gridLoader.removeClass('is-active');
                        $gallery.addClass('isotope-initialized');
                    });
                } else {
                    console.warn('imagesLoaded not found — skipping preloader sync');
                }
            } catch (e) {}

            applyIsotopeItemWidths();
            isoInstance = new Isotope($gallery[0], {
                itemSelector: '.eel-gallery-item',
                layoutMode: 'masonry',
                percentPosition: true,
                masonry: { columnWidth: '.eel-gallery-item' },
                transitionDuration: '0.35s',
                hiddenStyle: { opacity: 0, transform: 'scale(0.98)' },
                visibleStyle: { opacity: 1, transform: 'scale(1)' }
            });
            $gallery.addClass('isotope-initialized');

            $(window).on('resize', function () {
                applyIsotopeItemWidths();
                if (isoInstance) isoInstance.layout();
            });

            // Keep columns in sync in editor
            if (isEditMode && isoInstance) {
                setTimeout(function () { isoInstance.layout(); }, 200);
                var syncInterval = setInterval(function () {
                    if (!$gallery.closest('.elementor-editor-active').length) { clearInterval(syncInterval); return; }
                    applyIsotopeItemWidths();
                    isoInstance.layout();
                }, 500);
            }
        }

        // Filter click handler
        var filterDelayMs = 250;
        var filterTimerId = null;
        $scope.find('.eel-gallery-filters .eel-filter').on('click', function () {
            var $this = $(this), filter = $this.data('filter');
            $scope.find('.eel-gallery-filters .eel-filter').removeClass('active');
            $this.addClass('active');

            if (usingIsotope && isoInstance) {
                if (filterTimerId) { clearTimeout(filterTimerId); filterTimerId = null; }
                $gridLoader.addClass('is-active');
                filterTimerId = setTimeout(function () {
                    if (filter === '*') isoInstance.arrange({ filter: '*' });
                    else {
                        isoInstance.arrange({
                            filter: function () {
                                var cats = this.getAttribute('data-category') || '';
                                var arr = String(cats).split(' ');
                                return arr.indexOf(filter) !== -1;
                            }
                        });
                    }
                    $gridLoader.removeClass('is-active');
                }, filterDelayMs);
                return;
            }

            // CSS fallback
            var $items = $scope.find('.eel-gallery-item');
            var transitionMs = 250;
            $gallery.addClass('eel-grid-suspended');
            $gridLoader.addClass('is-active');
            if (filterTimerId) { clearTimeout(filterTimerId); filterTimerId = null; }
            filterTimerId = setTimeout(function () {
                $items.each(function () {
                    var $item = $(this), cats = $item.data('category'), matches = false;
                    if (cats) matches = String(cats).split(' ').indexOf(filter) !== -1;
                    if (filter === '*' || matches) {
                        $item.css('display', '');
                        requestAnimationFrame(function () { $item.removeClass('eel-is-hidden'); });
                    } else {
                        $item.addClass('eel-is-hidden');
                        setTimeout(function () { if ($item.hasClass('eel-is-hidden')) $item.css('display', 'none'); }, transitionMs);
                    }
                });
                $gridLoader.removeClass('is-active');
                $gallery.removeClass('eel-grid-suspended');
            }, filterDelayMs);
        });

        // Popup / lightbox functionality
        const $popupGallery = $scope.find('.eel-gallery-filter.eel-popup-enabled');
        if (!$popupGallery.length) return;

        let currentIndex = 0;
        const $lightbox = $scope.find('.eel-lightbox');
        const $lightboxImg = $lightbox.find('.eel-lightbox-image');
        let $loader = $lightbox.find('.eel-loader');
        if (!$loader.length) {
            $loader = $('<div class="eel-loader" aria-hidden="true"></div>');
            $lightbox.append($loader);
        }
        const $galleryLinks = $popupGallery.find('.eel-popup-link');

        function setLoading(isLoading) {
            if (isLoading) { $lightbox.addClass('loading'); $loader.addClass('is-active'); }
            else { $lightbox.removeClass('loading'); $loader.removeClass('is-active'); }
        }

        var loadTimeoutId = null, pendingImageObj = null;
        function clearPendingTimers() { if (loadTimeoutId) clearTimeout(loadTimeoutId); loadTimeoutId = null; pendingImageObj = null; }

        function preloadAndShowWithDelay(src, minMs) {
            clearPendingTimers();
            setLoading(true);
            $lightboxImg.css('opacity', 0);

            var delayDone = false, imageReady = false;
            function tryComplete() { if (delayDone && imageReady) { $lightboxImg.attr('src', src).css('opacity', 1); setLoading(false); } }

            loadTimeoutId = setTimeout(function () { delayDone = true; tryComplete(); }, Math.max(0, minMs || 0));

            var img = new Image();
            pendingImageObj = img;
            img.onload = img.onerror = function () {
                if (pendingImageObj !== img) return;
                imageReady = true;
                tryComplete();
            };
            img.src = src;
        }

        function openLightbox(index) {
            currentIndex = index;
            const imgSrc = $galleryLinks.eq(currentIndex).attr('href');
            setLoading(true);
            $lightbox.fadeIn(300).css('display', 'grid');
            preloadAndShowWithDelay(imgSrc, 400);
        }
        function showNext() { currentIndex = (currentIndex + 1) % $galleryLinks.length; preloadAndShowWithDelay($galleryLinks.eq(currentIndex).attr('href'), 400); }
        function showPrev() { currentIndex = (currentIndex - 1 + $galleryLinks.length) % $galleryLinks.length; preloadAndShowWithDelay($galleryLinks.eq(currentIndex).attr('href'), 400); }

        $galleryLinks.off('click').on('click', function (e) { e.preventDefault(); openLightbox($(this).data('index')); });
        $lightbox.find('.eel-next').off('click').on('click', showNext);
        $lightbox.find('.eel-prev').off('click').on('click', showPrev);
        $lightbox.find('.eel-close').off('click').on('click', function () { $lightbox.fadeOut(200); });
        $lightbox.off('click').on('click', function (e) { if ($(e.target).is('.eel-lightbox, .eel-close')) $lightbox.fadeOut(200); });
        $(document).off('keydown.eelLightbox').on('keydown.eelLightbox', function (e) {
            if ($lightbox.is(':visible')) {
                if (e.key === 'ArrowRight') showNext();
                else if (e.key === 'ArrowLeft') showPrev();
                else if (e.key === 'Escape') $lightbox.fadeOut(200);
            }
        });
    };

    // Elementor frontend and editor
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/eel-filterable-gallery.default',
            initEelFilterableGallery
        );

        // Initialize existing widgets in editor
        try {
            if (typeof elementorFrontend !== 'undefined' && elementorFrontend.isEditMode()) {
                $('.elementor-widget[data-widget_type="eel-filterable-gallery.default"]').each(function () {
                    initEelFilterableGallery($(this));
                });
            }
        } catch (e) {}
    });

})(jQuery);
