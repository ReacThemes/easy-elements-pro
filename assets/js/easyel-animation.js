(function($){
    // =================================
    // GSAP & ScrollTrigger
    // =================================
    if (typeof gsap === "undefined" || typeof ScrollTrigger === "undefined") return;
    gsap.registerPlugin(ScrollTrigger);
    // =================================
    // Elementor Animations
    // =================================
    var easyel_runAnimations = function($scope){

        function splitTextNodes($el, mode){
            $el.contents().each(function(){
                if(this.nodeType === 3){ // Text node
                    if(mode === 'chars'){
                        var chars = this.nodeValue.split('').map(l => '<em class="easy-split-letter">'+(l===' ' ? '&nbsp;' : l)+'</em>').join('');
                        $(this).replaceWith(chars);
                    } else if(mode === 'words'){
                        var words = this.nodeValue.split(' ').map(w => '<em class="easy-split-word">'+w+'</em>').join(' ');
                        $(this).replaceWith(words);
                    }
                } else if(this.nodeType === 1){ // Element node
                    splitTextNodes($(this), mode);
                }
            });
        }

        $scope.find('[data-eel-animation]').each(function(){
            var $wrap = $(this);
            var target = $wrap.find('.e-e-title');
            var animType = $wrap.data('eel-animation');
            if(!animType || animType==='default' || !target.length) return;

            switch(animType){
                case 'split-text':
                    splitTextNodes(target,'chars');
                    if(target.find('.easy-split-letter').length){
                        gsap.from(target.find('.easy-split-letter'), {
                            y:50, opacity:0, stagger:0.05, duration:0.6,
                            scrollTrigger:{trigger:$wrap[0], start:"top 80%"}
                        });
                    }
                    break;

                case 'split-words':
                    splitTextNodes(target,'words');
                    if(target.find('.easy-split-word').length){
                        gsap.from(target.find('.easy-split-word'), {
                            y:50, opacity:0, stagger:0.1, duration:0.6,
                            scrollTrigger:{trigger:$wrap[0], start:"top 80%"}
                        });
                    }
                    break;

                case 'typewriter':
                    var t = target.text(); target.text('');
                    gsap.to({length:0},{length:t.length,duration:2,ease:"none",
                        scrollTrigger:{trigger:$wrap[0], start:"top 80%"},
                        onUpdate:function(){ target.text(t.substr(0, Math.floor(this.targets()[0].length))); }
                    });
                    break;

                case 'text-bounce':
                    gsap.from(target,{y:20, opacity:0, ease:"bounce.out", duration:1,
                        scrollTrigger:{trigger:$wrap[0], start:"top 80%"}});
                    break;

                case 'split-lines': 
                case 'split-lines-left':
                case 'split-lines-up':
                    if(typeof SplitText !== "undefined"){
                        document.fonts.ready.then(()=>{
                            let split = new SplitText(target,{type:"lines,words", wordsClass:"split-words", linesClass:"split-lines"});
                            let vars = {stagger:0.15, duration:0.8, ease:"power3.out", scrollTrigger:{trigger:$wrap[0], start:"top 80%"}};
                            if(animType==='split-lines-left') vars.x=80, vars.opacity=0;
                            else if(animType==='split-lines-up') vars.y=-40, vars.opacity=0;
                            else vars.y=60, vars.opacity=0;
                            gsap.from(split.lines, vars);
                        });
                    }
                    break;
            }
        });
    };


	// =================================
	// Horizontal Scroll Section
	// =================================
	gsap.registerPlugin(ScrollTrigger);
		gsap.utils.toArray('.eel-scroll-image').forEach(section => {
		const images = section.querySelectorAll('.per-item');
		
		// Calculate total width
		let totalWidth = 0;
		images.forEach(img => {
			const style = getComputedStyle(img);
			const marginRight = parseFloat(style.marginRight) || 0;
			totalWidth += img.offsetWidth + marginRight;
		});

		// Set section width
		section.style.width = totalWidth + "px";

		// Horizontal scroll
		gsap.to(section, {
			x: () => `-${totalWidth - section.parentElement.offsetWidth}px`,
			ease: "none",
			scrollTrigger: {
			trigger: section.parentElement,
			start: "center center",
			end: () => "+=" + (totalWidth - section.parentElement.offsetWidth),
			pin: true,
			scrub: true,
			anticipatePin: 1,
			}
		});
	});


    // =================================
    // DOM Content Loaded Global Animations
    // =================================
    document.addEventListener('DOMContentLoaded', function(){
        // =================================
		// Parallax Backgrounds
		// =================================
		gsap.utils.toArray(".eel-parallax-bg").forEach(section=>{
            let size = section.dataset.parallaxSize || "180%";
            section.style.backgroundSize = size;

            let y = {value: 0}, 
                smooth = {value: 0};

            ScrollTrigger.create({
                trigger: section,
                start: "top bottom",
                end: "bottom top",
                scrub: 0.6,
                onUpdate: self => y.value = -self.progress * 80
            });

            gsap.ticker.add(()=>{
                smooth.value += (y.value - smooth.value) * 0.12;
                section.style.backgroundPosition = `center ${smooth.value}px`;
            });
        });

        // =================================
        // Vertical scroll images
        // =================================
        gsap.utils.toArray('.eel-vertical-scroll-img').forEach(div => {
            let yVal = div.dataset.scrollY || "20%";
            gsap.set(div, { y: 0 });
            gsap.to(div, {
                y: yVal,
                ease: "none",
                scrollTrigger: {
                    trigger: div,
                    pin: false,
                    scrub: 0.6
                }
            });
        });
       

        // Horizontal text scroll
        gsap.utils.toArray('.eel-hr-scroll-text').forEach(text=>{
            let dir=text.dataset.parallaxDirection||"left";
            let percent=text.dataset.parallaxPercent||36;
            let startX = dir==="left"? percent+"%" : "-"+percent+"%";
            gsap.set(text,{x:startX});
            gsap.to(text,{x:"0%", ease:"none", scrollTrigger:{trigger:text,start:"top bottom",end:"bottom top",scrub:0.5}});
        });

    
        // Zoom Out (small ➝ big)
        gsap.utils.toArray('.eel-hr-scroll-container').forEach(div => {
            const zoomWidth = div.dataset.zoomWidth || 90;
            const start     = div.dataset.zoomStart || "top 80%";
            const end       = div.dataset.zoomEnd   || "top 30%";

            gsap.set(div, { width: zoomWidth + "%", margin: "0 auto" });
            gsap.to(div, {
                width: "100%",
                ease: "none",
                scrollTrigger: {
                    trigger: div,
                    start: start,
                    end: end,
                    scrub: 0.5
                }
            });
        });

        // Zoom In (big ➝ small)
        gsap.utils.toArray('.eel-hr-scroll-container-small').forEach(div => {
            const zoomWidth = div.dataset.zoomWidth || 90;
            const start     = div.dataset.zoomStart || "top 80%";
            const end       = div.dataset.zoomEnd   || "top 30%";

            gsap.set(div, { width: "100%", margin: "0 auto" });
            gsap.to(div, {
                width: zoomWidth + "%",
                ease: "none",
                scrollTrigger: {
                    trigger: div,
                    start: start,
                    end: end,
                    scrub: 0.5
                }
            });
        });  
        
        
        gsap.registerPlugin(ScrollTrigger);
        document.querySelectorAll('.eel-flip-img-scroll').forEach(container => {
            const img = container.querySelector('img');
            if (!img) return;
            const rotateX = parseFloat(container.dataset.flipRotateX) || 30;
            gsap.fromTo(img, 
                { rotateX: -10 },  
                { 
                    rotateX: rotateX,
                    ease: "none",
                    scrollTrigger: {
                        trigger: container,
                        start: "top bottom",
                        end: "bottom top",
                        scrub: true,
                    }
                }
            );
        });



        // Image width animation
        gsap.utils.toArray('.eel-hr-scroll-image-size').forEach(img=>{
            gsap.set(img,{width:"0%",margin:"0 auto",display:"block"});
            gsap.to(img,{width:"100%", ease:"none", scrollTrigger:{trigger:img,start:"top 80%",end:"top 30%",scrub:0.5}});
        });

        // Custom cursor
        if(document.querySelector(".eel-cursor") && document.querySelector(".eel-cursor2")){
            let cursor = document.querySelector(".eel-cursor");
            let cursor2 = document.querySelector(".eel-cursor2");
            let mouseX=0, mouseY=0;
            let cursorScale = document.querySelectorAll(".e-e-title, a");

            gsap.to({},0.016,{repeat:-1,onRepeat:()=>{
                gsap.set(cursor,{css:{left:mouseX,top:mouseY}});
                gsap.to(cursor2,{duration:0.3,css:{left:mouseX,top:mouseY}});
            }});

            window.addEventListener("mousemove", e=>{ mouseX=e.clientX; mouseY=e.clientY; });

            cursorScale.forEach(el=>{
                el.addEventListener("mouseenter", ()=>{
                    cursor.classList.add("eel-grow");
                    if(el.classList.contains("ee-small")){ cursor.classList.remove("eel-grow"); cursor.classList.add("eel-grow-small"); }
                });
                el.addEventListener("mouseleave", ()=>{
                    cursor.classList.remove("eel-grow");
                    cursor.classList.remove("eel-grow-small");
                });
            });
        }
    });

    // =================================
    // Elementor Hook
    // =================================
    $(window).on('elementor/frontend/init', function(){
        // Run on widgets
        elementorFrontend.hooks.addAction('frontend/element_ready/widget', easyel_runAnimations);
        // Run on sections
        elementorFrontend.hooks.addAction('frontend/element_ready/section', easyel_runAnimations);
        // Run on columns
        elementorFrontend.hooks.addAction('frontend/element_ready/column', easyel_runAnimations);
    });


    function initEasyelCursorHover($scope){
        var cursor = $scope.find(".easyel-cursor-hover")[0];
        var projects = $scope.find(".easyel-cursor-hover-list");
        if(!cursor || !projects.length) return;
        gsap.set(cursor, {scale: 0.1, autoAlpha: 0, transformOrigin: "center center", position: "fixed", top:0, left:0, xPercent:-50, yPercent:-50});

        let mouseX = 0, mouseY = 0;
        let posX = 0, posY = 0;
        
        window.addEventListener("mousemove", e => {
            mouseX = e.clientX;
            mouseY = e.clientY;
        });

        gsap.ticker.add(() => {
            posX += (mouseX - posX) * 0.15;
            posY += (mouseY - posY) * 0.15;
            gsap.set(cursor, {x: posX, y: posY});
        });

        projects.each(function(){
            const project = this;
            const image = project.dataset.image;

            project.addEventListener("mouseenter", ()=>{
                cursor.style.backgroundImage = `url(${image})`;
                cursor.style.backgroundSize = "cover";
                cursor.style.backgroundPosition = "center";
                gsap.to(cursor, { scale:1, autoAlpha:1, duration:0.3, ease:"power3.out" });
            });

            project.addEventListener("mouseleave", ()=>{
                gsap.to(cursor, { scale:0.1, autoAlpha:0, duration:0.3, ease:"power3.out" });
            });
        });
    }

    // Elementor frontend init
    $(window).on('elementor/frontend/init', function(){
        elementorFrontend.hooks.addAction('frontend/element_ready/widget', initEasyelCursorHover);
        elementorFrontend.hooks.addAction('frontend/element_ready/section', initEasyelCursorHover);
    });

})(jQuery);

