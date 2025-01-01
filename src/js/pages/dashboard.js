document.addEventListener("DOMContentLoaded", () => {

    interactiveForm();
})

function interactiveForm() {
    //input 'portion'
    const portionInput = document.getElementById('portion') || null;
    // elements
    const foodDetailValues = document.querySelectorAll('.food-detail_value') || null;

    if (!portionInput || !foodDetailValues) return;

    //initial portsion size
    const initialPortionSize = parseFloat(portionInput.getAttribute('data-portion-initial-size'));

    //listen to input changes
    portionInput.addEventListener('input', calcProportions);

    //execute when bringing the info
    calcProportions();

    function calcProportions() {

        // get new value
        const newPortionSize = parseFloat(portionInput.value);
        //validate number
        if (isNaN(newPortionSize) || newPortionSize <= 0) return;

        // update value of the nutrients to the new portion
        foodDetailValues.forEach(element => {
            //initial value
            const initialValue = parseFloat(element.getAttribute('data-initial-value'));

            // validate number and appply rule of three
            if (!isNaN(initialValue)) {
                const updatedValue = (initialValue * newPortionSize) / initialPortionSize;
                // update value
                element.textContent = updatedValue.toFixed(1);
            }
        });
    }

}

