const sectionNumber = 3;
let sectionStep = 2;
let previousSectionStep = null;

document.addEventListener('DOMContentLoaded', function () {
    initTabs();
})

function initTabs() {

    updateTabStyles();

    showSection();

    const tabs = document.querySelector('.tab-container');

    tabs.addEventListener('click', changeSection)
}

function changeSection(e) {
    if (!e.target.classList.contains('tab')) return;

    //obtain the number of the tab clicked
    const tabNumber = e.target.dataset.sectionstep || null;
    if (!tabNumber) return;

    //validate if it's a valid section number (sectionNumber variable)
    if (tabNumber < 1 || tabNumber > sectionNumber) return;

    //return if it's the current section (sectionStep variable)
    if (tabNumber == sectionStep) return;

    //update the previous section number
    previousSectionStep = sectionStep

    // update the sectionStep variable with the new section number
    sectionStep = tabNumber;

    //update tab styles
    updateTabStyles();

    // hide the previous section
    hidePreviousSection();

    // 8. show the new section
    showSection();
}

function updateTabStyles() {
    // add the class tab--active to the tab related to the sectionStep
    const activeTab = document.querySelector(`[data-sectionstep="${sectionStep}"]`) || null;
    if (!activeTab) return;
    activeTab.classList.add('tab--active');

    // remove the class tab--active from the previous tab
    if (!previousSectionStep) return;
    const previousTab = document.querySelector(`[data-sectionstep="${previousSectionStep}"]`) || null;
    if (!previousTab) return;
    previousTab.classList.remove('tab--active');

}

function hidePreviousSection() {
    // remove the class section--show from the previous section
    const previousSection = document.querySelector(`[data-section-id="${previousSectionStep}"]`) || null;
    if (!previousSection) return;
    previousSection.classList.add('section--hide');
}

function showSection() {
    // add the class section--show to the section with the same 'data-section-id' as the tab
    const section = document.querySelector(`[data-section-id="${sectionStep}"]`) || null;
    if (!section) return;
    section.classList.remove('section--hide');
}

