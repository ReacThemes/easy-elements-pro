class Sticker {
    constructor(obj) {
        this.btns = document.querySelectorAll('.eel-sticky-w');
        this.btns.forEach(b => {
            const text = b.querySelector('.eel-sticky-t');
            const inertion = parseFloat(b.getAttribute("data-sticky")) || 5;
            let targetX = 0, targetY = 0;
            let currentX = 0, currentY = 0;
            function animate() {
                currentX += (targetX - currentX) * obj.smoothness;
                currentY += (targetY - currentY) * obj.smoothness;
                text.style.transform = `translate(${currentX}px, ${currentY}px)`;
                requestAnimationFrame(animate);
            }
            animate();

            b.addEventListener("mousemove", e => {
                const rect = b.getBoundingClientRect();
                const offsetX = e.clientX - rect.left;
                const offsetY = e.clientY - rect.top;
                targetX = (offsetX - rect.width / 2) / inertion;
                targetY = (offsetY - rect.height / 2) / inertion;
            });
            b.addEventListener("mouseleave", () => {
                targetX = 0;
                targetY = 0;
            });
        });
    }
}

// Elementor support (Frontend + Editor)
function initStickerEffect(){
    new Sticker({
        smoothness: 0.2
    });
}
document.addEventListener("DOMContentLoaded", initStickerEffect);
// Elementor Editor/Preview Mode
jQuery(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction("frontend/element_ready/global", function(){
        initStickerEffect();
    });
});
