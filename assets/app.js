import './styles/app.css';

// Scroll animations with IntersectionObserver
document.addEventListener('DOMContentLoaded', () => {
    const animatedElements = document.querySelectorAll('[data-animate]');

    if (animatedElements.length === 0) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px',
    });

    animatedElements.forEach((el) => observer.observe(el));

    // Mobile menu toggle
    const menuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');

    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // FAQ accordions
    document.querySelectorAll('[data-accordion-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const content = button.nextElementSibling;
            const icon = button.querySelector('[data-accordion-icon]');

            content.classList.toggle('hidden');
            if (icon) {
                icon.classList.toggle('rotate-180');
            }
        });
    });
});

// Portfolio carousel (separate from the main DOMContentLoaded to avoid early return)
function initCarousel() {
    const track = document.getElementById('carousel-track');
    const prevBtn = document.getElementById('carousel-prev');
    const nextBtn = document.getElementById('carousel-next');
    const dots = document.querySelectorAll('[data-carousel-dot]');

    if (!track || !prevBtn || !nextBtn || track.dataset.carouselInit) return;
    track.dataset.carouselInit = 'true';

    let current = 0;
    const total = track.children.length;

    function goTo(index) {
        current = (index + total) % total;
        track.style.transform = `translateX(-${current * 100}%)`;
        dots.forEach((dot, i) => {
            dot.classList.toggle('bg-vc-blue', i === current);
            dot.classList.toggle('bg-slate-300', i !== current);
            dot.classList.toggle('hover:bg-slate-400', i !== current);
        });
    }

    prevBtn.addEventListener('click', () => goTo(current - 1));
    nextBtn.addEventListener('click', () => goTo(current + 1));
    dots.forEach((dot) => {
        dot.addEventListener('click', () => goTo(parseInt(dot.dataset.carouselDot)));
    });
}

document.addEventListener('DOMContentLoaded', initCarousel);
document.addEventListener('turbo:load', initCarousel);
