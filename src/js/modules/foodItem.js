document.addEventListener("DOMContentLoaded", function () {
    const mealGroups = document.querySelectorAll('.meal-summary__group');

    mealGroups.forEach(mealGroup => {
        mealGroup.addEventListener("click", handleClickEvents);
    })

});

function handleClickEvents(e) {
    const targetElement = e.target;

    //show || hide toggle options
    if (targetElement.classList.contains('options-toggle')) showToggle(targetElement);

}

function showToggle(toggle) {
    const options = toggle.nextElementSibling;
    options.classList.toggle("hidden");

    document.addEventListener("click", function (e) {
        if (!toggle.contains(e.target) && !options.contains(e.target)) {
            options.classList.add("hidden");
        }
    });
}