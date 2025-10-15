(function (window, document, $, undefined) {
    "use strict";

    var EasyLiveCopy = {
        // Global selector to store all relevant sections/containers
        _elItems: null,

        EasyElementorSelector: function () {
            this._elItems = $(
                '[data-elementor-type="wp-page"] > [data-element_type="container"], [data-elementor-type="wp-post"] > [data-element_type="container"], [data-elementor-type="wp-page"] > [data-element_type="section"], [data-elementor-type="wp-post"] > [data-element_type="section"]'
            );

            // Fallback for top sections
            if (this._elItems.length === 0) {
                this._elItems = $(".elementor-section.elementor-top-section");
            }
        },

        //  Function to add live copy buttons
        LiveCopyBtn: function () {
            var self = this;

            // Loop through all sections
            this._elItems.each(function (index) {
                var $section = $(this);

                // Check if section has content
                var hasContent =
                $.trim($section.find(".elementor-widget-wrap").html()) ||
                $.trim($section.find(".e-con-inner").html()) ||
                $.trim($section.find(".e-child").html()) ||
                $.trim($section.find(".elementor-section-wrap").html()) ||
                $.trim($section.find(".elementor-column-wrap").html()) ||
                $.trim($section.find(".elementor-container").html()) ||
                $.trim($section.find(".elementor-row").html()) ||
                $.trim($section.find(".elementor-column").html()) ||
                $.trim($section.find(".elementor-element").html());

                if (hasContent) {
                    // Avoid adding button multiple times
                    if ($section.find(".easy-live-elements-wrapper").length === 0) {

                        let $parentContainer = $section
                        .find(".e-con-inner")
                        .parent();
                        let parentDataId = $parentContainer.attr("data-id");

                        let parentClasses = $parentContainer.attr("class") || "";

                        if (parentClasses.includes("easy-live-copy-enabled-yes")) {

                            // Button HTML
                            let buttonHTML = `
                                <div class="easy-live-elements-wrapper">
                                    <button data-id="${parentDataId}" class="easy-live-copy-button" title="Copy">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M10 1.5A1.5 1.5 0 0 1 11.5 3v9A1.5 1.5 0 0 1 10 13.5H4A1.5 1.5 0 0 1 2.5 12V3A1.5 1.5 0 0 1 4 1.5h6zm0 1H4a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h6a.5.5 0 0 0 .5-.5V3a.5.5 0 0 0-.5-.5z"/>
                                            <path d="M13.5 3.5v9a1.5 1.5 0 0 1-1.5 1.5H11v-1h1a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5H11v-1h1a1.5 1.5 0 0 1 1.5 1.5z"/>
                                        </svg>
                                    </button>
                                    <div class="easy-live-separator"></div>
                                    <button data-id="${parentDataId}" class="easy-elements-json-download" title="Download">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 16a1 1 0 0 1-.707-.293l-4-4a1 1 0 0 1 1.414-1.414L11 12.586V3a1 1 0 1 1 2 0v9.586l2.293-2.293a1 1 0 0 1 1.414 1.414l-4 4A1 1 0 0 1 12 16z"/>
                                            <path d="M5 18a1 1 0 0 0 0 2h14a1 1 0 0 0 0-2H5z"/>
                                        </svg>
                                    </button>
                                </div>
                                `;
                            $section.append(buttonHTML);
                        }
                    }
                }
            });
        },

        DownloadHandler: function () {
            $(document).on("click", ".easy-elements-json-download", function (e) {
                e.preventDefault();

                let sectionId = $(this).data("id");
                let postId = elementorFrontendConfig?.post?.id || 0;

                $.ajax({
                url: easy_live_copypaste.ajax_url,
                type: "POST",
                dataType: "json",
                data: {
                    action: "easy_download_section_json",
                    security: easy_live_copypaste.nonce,
                    section_id: sectionId,
                    post_id: postId,
                },
                success: function (response) {
                    if (response.success && response.data.json_url) {
                    const a = document.createElement("a");
                    a.href = response.data.json_url;
                    a.download = response.data.filename;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    } else {
                    alert("Failed to generate JSON!");
                    }
                },
                });
            });
        },

        CopyHandler: function () {
            $(document).on("click", ".easy-live-copy-button", function (e) {
                e.preventDefault();

                let $btn = $(this);
                let sectionId = $btn.data("id");
                let postId = elementorFrontendConfig?.post?.id || 0;

                // Disable button and show loading spinner
                $btn.addClass("is-copying").attr("disabled", true);
                $btn.html(`
                    <svg class="spin" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                    </svg>
                `);

                $.ajax({
                    url: easy_live_copypaste.ajax_url,
                    type: "POST",
                    dataType: "json",
                    data: {
                        action: "easy_copy_section_json",
                        security: easy_live_copypaste.nonce,
                        section_id: sectionId,
                        post_id: postId,
                    },
                    success: function (response) {
                        if (response.success && response.data.json_url) {
                            fetch(response.data.json_url)
                                .then(res => res.text())
                                .then(jsonText => {
                                    navigator.clipboard.writeText(jsonText).then(() => {
                                        // Show success visual
                                        $btn
                                            .removeClass("is-copying")
                                            .addClass("copy-success")
                                            .html(`
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M13.485 1.929a.75.75 0 0 1 1.06 1.06L6.56 10.975a.75.75 0 0 1-1.06 0L1.454 6.93a.75.75 0 1 1 1.06-1.06l3.016 3.015 7.955-7.956z"/>
                                                </svg>
                                            `);

                                        // Revert back after 2s
                                        setTimeout(() => {
                                            $btn.removeClass("copy-success").removeAttr("disabled").html(`
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M10 1.5A1.5 1.5 0 0 1 11.5 3v9A1.5 1.5 0 0 1 10 13.5H4A1.5 1.5 0 0 1 2.5 12V3A1.5 1.5 0 0 1 4 1.5h6zm0 1H4a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h6a.5.5 0 0 0 .5-.5V3a.5.5 0 0 0-.5-.5z"/>
                                                    <path d="M13.5 3.5v9a1.5 1.5 0 0 1-1.5 1.5H11v-1h1a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5H11v-1h1a1.5 1.5 0 0 1 1.5 1.5z"/>
                                                </svg>
                                            `);
                                        }, 3000 );
                                    });
                                });
                        } else {
                            $btn.removeClass("is-copying").removeAttr("disabled").text("Failed!");
                            setTimeout(() => {
                                $btn.text("Copy");
                            }, 2000);
                        }
                    },
                    error: function () {
                        $btn.removeClass("is-copying").removeAttr("disabled").text("Error!");
                        setTimeout(() => {
                            $btn.text("Copy");
                        }, 2000);
                    }
                });
            });
        },


        init: function () {
            this.EasyElementorSelector();
            if ( ( easy_live_copypaste.easy_logged_in_user == 1 ) ) {
                if (document.body.classList.contains('logged-in')) {
                    this.LiveCopyBtn();
                }
            } else {
                this.LiveCopyBtn();
            }
        
            this.DownloadHandler();
            this.CopyHandler();
        },
    };

    // Initialize on document ready
    jQuery(document).ready(function ($) {
        EasyLiveCopy.init();
    });
})(window, document, jQuery);