(function($){
    "use strict";
    // Function to initialize portfolio hover effect
    function initPortfolioHover($scope) {
        var $portfolioItems = $scope.find('.eel-portfolio-item-pro.pro-skin1');
        if ($portfolioItems.length === 0) return;
        $portfolioItems.each(function() {
            var $portfolioItem = $(this);
            var $portfolioInfos = $portfolioItem.find('.eel-portfolio-info-pro');
            var $images = $portfolioItem.find('.eel-portfolio-img');
            // Initially hide all images except first
            $images.removeClass('active').css({
                'opacity': '0',
                'visibility': 'hidden',
                'transform': 'scale(1.05)',
                'transition': 'opacity 0.4s ease, visibility 0.4s ease, transform 0.4s ease'
            });
            if ($images.length > 0) {
                $images.first().addClass('active').css({
                    'opacity': '1',
                    'visibility': 'visible',
                    'transform': 'scale(1)'
                });
            }
            // Hover effect
            $portfolioInfos.off('mouseenter click').on('mouseenter click', function(e){
                if(e.type === 'click') e.preventDefault();
                var $this = $(this);
                var index = parseInt($this.data('index'));
                var $targetImage = $images.filter('[data-index="' + index + '"]');
                var hoverSrc = $targetImage.data('hover-src');
                $portfolioInfos.removeClass('active');
                $this.addClass('active');
                $images.removeClass('active').css({
                    'opacity': '0',
                    'visibility': 'hidden',
                    'transform': 'scale(1.05)'
                });
                if ($targetImage.length) {
                    $targetImage.addClass('active').css({
                        'opacity': '1',
                        'visibility': 'visible',
                        'transform': 'scale(1)'
                    });
                    if (hoverSrc && hoverSrc !== $targetImage.attr('src')) {
                        var img = new Image();
                        img.onload = function(){ $targetImage.attr('src', hoverSrc); }
                        img.src = hoverSrc;
                    }
                }
            });
        });
    }
    // Elementor frontend hook
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/eel-portfolio-pro.default', function($scope){
            initPortfolioHover($scope);
        });
    });

})(jQuery);
