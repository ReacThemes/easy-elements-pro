(function ($) {
    "use strict";

    // Listen for Ctrl+V (or Cmd+V)
    $(window).on("keydown", async function (e) {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === "v") {
            try {
                const text = await navigator.clipboard.readText();
                const json = JSON.parse(text);

                // ✅ Check if valid Elementor section data
                if (!json.content || !Array.isArray(json.content)) return;

                const section = json.content[0];
                if (!section.id || section.elType !== "section" && section.elType !== "container") {
                    alert("Invalid Elementor section data!");
                    return;
                }

                // 🧠 Use Elementor internal importer
                $e.run("document/elements/import", {
                    model: section,
                    withPageSettings: false,
                });

                elementor.notifications.showToast({
                    message: "✅ Section pasted successfully!",
                });
            } catch (err) {
                console.error(err);
            }
        }
    });
})(jQuery);
