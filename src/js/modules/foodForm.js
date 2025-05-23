document.addEventListener("DOMContentLoaded", () => {

    formToggle();

    calcCalories();
});


function formToggle() {

    const formToggle = document.getElementById('toggle-advanced') || null;

    if (!formToggle) return;

    formToggle.addEventListener('click', function () {
        const advancedFields = document.getElementById('advanced-fields');
        advancedFields.classList.toggle('food-creation__fieldset--hidden');

        this.textContent = advancedFields.classList.contains('food-creation__fieldset--hidden')
            ? 'Más opciones'
            : 'Menos opciones';
    });

}

function calcCalories() {
    // elements to calculate total calories
    const foodCalorieInput = document.getElementById("food-calories");
    const foodFatInput = document.getElementById("food-fat");
    const foodCarbsInput = document.getElementById("food-carbohydrate");
    const foodProteinInput = document.getElementById("food-protein");

    if (!foodCalorieInput || !foodFatInput || !foodCarbsInput || !foodProteinInput) return;

    foodFatInput.addEventListener('input', calcTotalCal);
    foodCarbsInput.addEventListener('input', calcTotalCal);
    foodProteinInput.addEventListener('input', calcTotalCal);

    function calcTotalCal() {
        // Validate and convert the values
        const fatTotal = parseFloat(foodFatInput.value) || 0;
        const carbTotal = parseFloat(foodCarbsInput.value) || 0;
        const proteinTotal = parseFloat(foodProteinInput.value) || 0;

        // Calc total calories
        const totalCalories = (fatTotal * 9) + (carbTotal * 4) + (proteinTotal * 4);
        foodCalorieInput.value = totalCalories.toFixed(1); // 1 decimal
    }
}
