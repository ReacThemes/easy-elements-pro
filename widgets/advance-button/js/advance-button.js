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

// Magnetic Button 
function magneticButton(){

    if (typeof gsap === "undefined") {
        console.error("GSAP not loaded!");
        return;
    }
    class magneticAnimation {
        constructor(el) {
            this.btn = el;
            this.flair = el.querySelector(".eel-advance-button-magnetic .eel-magnetic-btn-overly");
            if (!this.flair) return;
            // GSAP setters (for performance)
            this.xSet = gsap.quickSetter(this.flair, "xPercent");
            this.ySet = gsap.quickSetter(this.flair, "yPercent");
            // Initialize event listeners
            this.events();
        }

        getXY(e) {
            const rect = this.btn.getBoundingClientRect();
            const x = gsap.utils.clamp(0, 100, gsap.utils.mapRange(0, rect.width, 0, 100, e.clientX - rect.left));
            const y = gsap.utils.clamp(0, 100, gsap.utils.mapRange(0, rect.height, 0, 100, e.clientY - rect.top));
            return { x, y };
        }

        events() {
            const btn = document.querySelector('.eel-advance-button-magnetic');
            const magneticDuration = parseFloat(btn.getAttribute('data-duration')) || 0.5;

            console.log(magneticDuration);
            this.btn.addEventListener("mouseenter", (e) => {
                const { x, y } = this.getXY(e);
                this.xSet(x);
                this.ySet(y);
                gsap.to(this.flair, {
                scale: 1,
                duration: magneticDuration,
                ease: "power2.out"
                });
            });
            this.btn.addEventListener("mouseleave", (e) => {
                const { x, y } = this.getXY(e);
                gsap.killTweensOf(this.flair);
                gsap.to(this.flair, {
                xPercent: x > 90 ? x + 20 : x < 10 ? x - 20 : x,
                yPercent: y > 90 ? y + 20 : y < 10 ? y - 20 : y,
                scale: 0,
                duration: magneticDuration,
                ease: "power2.out"
                });
            });
            this.btn.addEventListener("mousemove", (e) => {
                const { x, y } = this.getXY(e);
                gsap.to(this.flair, {
                xPercent: x,
                yPercent: y,
                duration: magneticDuration,
                ease: "power2"
                });
            });
        }
    }
    document.querySelectorAll(".eel-advance-button-magnetic").forEach((el) => new magneticAnimation(el));
}

// Function Run After DOM Load 
document.addEventListener("DOMContentLoaded", function () {
  initStickerEffect();
  magneticButton();
});

// Elementor Editor/Preview Mode
jQuery(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction("frontend/element_ready/global", function($scope, $){
        initStickerEffect();
        $scope.find('.eel-advance-button-magnetic').each(function(){
            magneticButton($(this)[0]);
        });
    });
});
