// Simple fade-in trigger on scroll
const elements = document.querySelectorAll('.search-box, .hero-text');

window.addEventListener('scroll', () => {
    elements.forEach(el => {
        const rect = el.getBoundingClientRect().top;
        if (rect < window.innerHeight - 100) {
            el.style.opacity = "1";
            el.style.transform = "translateY(0)";
        }
    });
});
