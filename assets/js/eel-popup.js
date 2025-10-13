(function ($) {
	'use strict';

	// Video Popup Class
	document.addEventListener('DOMContentLoaded', function() {
		const popup = document.querySelector('.entro-all-video-popup-wrap');
		const wrapper = popup.querySelector('.entro-all-video-popup-iframe-wrapper');
		const closeBtn = popup.querySelector('.entro-all-video-popup-close');
	
		document.addEventListener('click', function(e) {
			const btn = e.target.closest('.entro-all-video-popup');
			if (!btn) return;
	
			e.preventDefault();
			const src = btn.getAttribute('data-video-src');
			const type = btn.getAttribute('data-video-type');
	
			let embed = '';
			if (type === 'youtube') {
				const ytID = src.includes('v=') ? src.split('v=')[1] : src;
				embed = `<iframe src="https://www.youtube.com/embed/${ytID}?autoplay=1" frameborder="0" allowfullscreen></iframe>`;
			} else if (type === 'vimeo') {
				const vimeoID = src.split('/').pop();
				embed = `<iframe src="https://player.vimeo.com/video/${vimeoID}?autoplay=1" frameborder="0" allowfullscreen></iframe>`;
			} else {
				embed = `<video controls autoplay><source src="${src}" type="video/mp4"></video>`;
			}
	
			wrapper.innerHTML = embed;
			popup.classList.add('active');
		});
	
		function closePopup() {
			popup.classList.remove('active');
			wrapper.innerHTML = '';
		}
	
		// Close popup handlers
		closeBtn.addEventListener('click', closePopup);
		popup.addEventListener('click', e => {
			if (e.target === popup) closePopup();
		});
		document.addEventListener('keyup', e => {
			if (e.key === 'Escape') closePopup();
		});
	});	

})(jQuery);