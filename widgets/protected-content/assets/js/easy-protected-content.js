(function ($) {
    "use strict";
    $(document).ready(function () {
        $('.easy-content-protected-form').on('submit', function () {
            $(this).find('button').text('Processing...');
        });
    });
})(jQuery);
