
class EasyelImageAccordion {
    constructor($scope) {
        this.accordion = $scope.find('.easyel-image-accordion')[0];
        
        if (!this.accordion) {
            return;
        }

        this.items = this.accordion.querySelectorAll('.easyel-image-item');
        this.count = this.items.length;
        if (this.count === 0) {
            return;
        }

        this.init();
    }

    init() {
        this.items.forEach(item => {
            item.addEventListener('mouseenter', () => {
                if (item.classList.contains('active')) {
                    return;
                }
                this.items.forEach(i => i.classList.remove('active'));
                item.classList.add('active');
                this.applyFlex();
            });
        });
        const activeItem = this.accordion.querySelector('.easyel-image-item.active');
        if (!activeItem && this.items.length > 0) {
            this.items[0].classList.add('active');
        }
        this.applyFlex();
    }

    applyFlex() {
        let activeWidth, otherWidth;

        if (this.count === 1) {
            activeWidth = 100;
            otherWidth = 0;
        } else if (this.count === 2) {
            activeWidth = 60;
            otherWidth = 40;
        } else {
            activeWidth = 50; 
            otherWidth = (100 - activeWidth) / (this.count - 1); 
        }

        this.items.forEach(item => {
            if (item.classList.contains('active')) {
                item.style.flex = `${activeWidth}%`;
            } else {
                item.style.flex = `${otherWidth}%`;
            }
        });
    }
}

jQuery(window).on('elementor/frontend/init', () => {
    elementorFrontend.hooks.addAction('frontend/element_ready/eel-image-accordion.default', ($scope) => {
        new EasyelImageAccordion($scope);
    });
});
