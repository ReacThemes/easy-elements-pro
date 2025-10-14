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
                    let parentDataId = $section
                    .find(".e-con-inner")
                    .parent()
                    .attr("data-id");

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
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M.5 9.9a.5.5 0 0 1 .5-.4h4V2.5a.5.5 0 0 1 1 0v7h4a.5.5 0 0 1 .4.8l-4.5 5a.5.5 0 0 1-.8 0l-4.5-5a.5.5 0 0 1-.1-.4z"/>
                                    <path d="M15.5 14a.5.5 0 0 1-.5.5H1a.5.5 0 0 1 0-1h14a.5.5 0 0 1 .5.5z"/>
                                </svg>
                            </button>
                        </div>
                        `;

                    $section.append(buttonHTML);
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

    // CopyHandler: function () {
    //     $(document).on("click", ".easy-live-copy-button", function (e) {
    //         e.preventDefault();

    //         let sectionId = $(this).data("id");
    //         let postId = elementorFrontendConfig?.post?.id || 0;

    //         $.ajax({
    //             url: easy_live_copypaste.ajax_url,
    //             type: "POST",
    //             dataType: "json",
    //             data: {
    //                 action: "easy_download_section_json",
    //                 security: easy_live_copypaste.nonce,
    //                 section_id: sectionId,
    //                 post_id: postId,
    //             },
    //             success: function (response) {
    //                 if (response.success && response.data.json_url) {
    //                     // Copy JSON directly to clipboard
    //                     fetch(response.data.json_url)
    //                         .then(res => res.text())
    //                         .then(jsonText => {
    //                             navigator.clipboard.writeText(jsonText).then(() => {
    //                                 alert("Section copied! Go to Elementor editor and paste (Ctrl+V)");
    //                             });
    //                         });
    //                 } else {
    //                     alert("Failed to copy section!");
    //                 }
    //             },
    //         });
    //     });
    // },

    CopyHandler: function () {
        $(document).on("click", ".easy-live-copy-button", function (e) {
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
                        fetch(response.data.json_url)
                            .then(res => res.json())
                            .then(jsonData => {
                                // Wrap in proper Elementor format
                                const exportJSON = {
                                    content: [jsonData],
                                    type: jsonData.elType || "section",
                                    title: document.title,
                                    version: elementorFrontendConfig?.version || "3.32.4",
                                    page_settings: [],
                                };

                                navigator.clipboard.writeText(JSON.stringify(exportJSON))
                                    .then(() => {
                                        alert("Section copied! Go to Elementor editor and press Ctrl+V to paste.");
                                    });
                            });
                    } else {
                        alert("Failed to copy section!");
                    }
                },
            });
        });
    },

    init: function () {
            this.EasyElementorSelector();
            this.LiveCopyBtn();
            this.DownloadHandler();
            this.CopyHandler();
        },
    };

    // Initialize on document ready
    jQuery(document).ready(function ($) {
        EasyLiveCopy.init();
    });
})(window, document, jQuery);