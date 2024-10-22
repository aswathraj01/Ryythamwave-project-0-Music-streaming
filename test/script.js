const tabs = document.querySelectorAll('.tabs button');
const activeSlide = document.querySelector('.active-slide');

// Set the initial position of the active slide
function setActiveSlide() {
    const activeTab = document.querySelector('.tabs button.active');
    activeSlide.style.width = activeTab.offsetWidth + 'px'; // Set width to active tab width
    activeSlide.style.left = activeTab.offsetLeft + 'px'; // Set left position to active tab position
}

// Set initial slide position
setActiveSlide();

// Change active tab on click
tabs.forEach(tab => {
    tab.addEventListener('click', () => {
        tabs.forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        setActiveSlide();
    });
});

