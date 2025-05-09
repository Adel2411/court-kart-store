/**
 * Hero Section Animations for Centered Hero
 */
document.addEventListener('DOMContentLoaded', function() {
    // Animate elements on page load
    animateHeroElements();
    
    // Initialize counters
    startCounters();
    
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
 * Start number counter animations
 */
function startCounters() {
    const counters = document.querySelectorAll('.counter-animation');
    
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'), 10);
        const duration = 2000; // 2 seconds
        const step = Math.ceil(target / (duration / 30)); // Update every 30ms
        let current = 0;
        
        const updateCounter = () => {
            current += step;
            
            if (current > target) {
                counter.textContent = target;
            } else {
                counter.textContent = current;
                setTimeout(updateCounter, 30);
            }
        };
        
        // Start counter after a delay
        setTimeout(updateCounter, 800);
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
