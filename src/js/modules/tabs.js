document.addEventListener('DOMContentLoaded', function () {
    initTabs();
})

function initTabs() {
    updateTabStyles();
}

function updateTabStyles() {
    // add the class tab--active to the tab related to the current url
    const tabs = document.querySelectorAll('.tab') || null;
    if (!tabs) return;

    const currentPath = window.location.pathname;

    tabs.forEach(tab => {
        if (tab.getAttribute('href') === currentPath) {
            tab.classList.add('tab--active');
        }
    });
}

