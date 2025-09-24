(function ($) {
    "use strict";

    function initEeTabs($scope) {
        const wrapper = $scope.find(".ee-tabs-wrapper");

        if (!wrapper.length) return;

        const isMobile = window.innerWidth <= 991;

        const tabTitles = wrapper.find(".ee-tab-titles li");
        const tabContents = wrapper.find(".ee-tab-content");
        const accordionToggles = wrapper.find(".ee-accordion-toggle");

        if (isMobile) {
            // Accordion Logic            
            accordionToggles.off("click").on("click", function () {
                const $this = $(this);
                const targetId = $this.data("tab");
                const content = wrapper.find("#" + targetId);

                if (content.hasClass("active")) {
                    content.removeClass("active").slideUp(300);
                    $this.removeClass("active");
                } else {
                    // Remove previous active
                    accordionToggles.removeClass("active");
                    tabContents.removeClass("active").slideUp(300);

                    // Add active to current
                    $this.addClass("active");
                    content.addClass("active").slideDown(300, function () {
                        const contentOffset = content.offset().top;
                        const windowHeight = $(window).height();
                        const contentHeight = content.outerHeight();
                        const scrollTo = contentOffset - (windowHeight / 2) + (contentHeight / 2);
                        $('html, body').animate({
                            scrollTop: scrollTo
                        }, 400);
                    });
                }
            });

            // Initial state: First tab open
            tabContents.removeClass("active");
            const firstToggle = accordionToggles.first();
            const firstTabId = firstToggle.data("tab");
            const firstContent = wrapper.find("#" + firstTabId);
            firstToggle.addClass("active");
            firstContent.addClass("active").show();

        } else {
            // Desktop Tabs
            tabTitles.off("click").on("click", function () {
                const $this = $(this);
                const targetId = $this.data("tab");

                tabTitles.removeClass("active");
                $this.addClass("active");

                tabContents.removeClass("active");
                wrapper.find("#" + targetId).addClass("active");
            });

            // Initial state
            tabTitles.first().addClass("active");
            tabContents.removeClass("active").first().addClass("active");
        }
    }

    $(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction("frontend/element_ready/global", initEeTabs);
    });

})(jQuery);
