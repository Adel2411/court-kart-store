/**
 * Hero Section Animations for Centered Hero
 */
document.addEventListener('DOMContentLoaded', function() {
    // Animate elements on page load
    animateHeroElements();
    
    // Scroll indicator animation
    animateScrollIndicator();
});

/**
 * Animate hero elements based on data-animation attribute
 */
function animateHeroElements() {
    const animatedElements = document.querySelectorAll('[data-animation]');
    
    // Delay each element animation slightly
    let delay = 0.2;
    
    animatedElements.forEach(element => {
        const animationType = element.getAttribute('data-animation');
        element.style.opacity = '0';
        
        setTimeout(() => {
            element.style.opacity = '1';
            
            switch(animationType) {
                case 'fade-in':
                    element.classList.add('animated-fade-in');
                    break;
                case 'fade-in-delay':
                    element.classList.add('animated-fade-in-delay');
                    break;
                case 'slide-up':
                    element.classList.add('animated-slide-up');
                    break;
                case 'slide-up-delay':
                    element.classList.add('animated-slide-up-delay');
                    break;
                case 'slide-up-delay-2':
                    element.classList.add('animated-slide-up-delay-2');
                    break;
                default:
                    element.classList.add('animated-fade-in');
            }
        }, delay * 1000);
        
        delay += 0.15;
    });
}

/**
 * Animate scroll indicator
 */
function animateScrollIndicator() {
    const indicator = document.querySelector('.hero-scroll-indicator');
    
    if (indicator) {
        setTimeout(() => {
            indicator.classList.add('visible');
        }, 2000);
    }
}
