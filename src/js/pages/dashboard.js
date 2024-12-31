// Obtén el input 'portion' y todos los elementos con la clase 'food-detail_value'
const portionInput = document.getElementById('portion');
const foodDetailValues = document.querySelectorAll('.food-detail_value');

// Obtén el tamaño inicial de la porción desde el atributo data-portion-initial-size
const initialPortionSize = parseFloat(portionInput.getAttribute('data-portion-initial-size'));

// Agrega un listener al input para detectar cambios
portionInput.addEventListener('input', () => {
    // Obtén el nuevo valor de la porción ingresado por el usuario
    const newPortionSize = parseFloat(portionInput.value);

    // Asegúrate de que el valor ingresado sea válido antes de actualizar
    if (isNaN(newPortionSize) || newPortionSize <= 0) {
        return;
    }

    // Itera sobre todos los elementos con la clase 'food-detail_value'
    foodDetailValues.forEach(element => {
        // Obtén el valor inicial del atributo data-initial-value
        const initialValue = parseFloat(element.getAttribute('data-initial-value'));

        // Si el elemento tiene un valor inicial válido, aplica la regla de tres
        if (!isNaN(initialValue)) {
            const updatedValue = (initialValue * newPortionSize) / initialPortionSize;
            // Actualiza el contenido del elemento con el nuevo valor
            element.textContent = updatedValue.toFixed(1); // Redondear a 2 decimales
        }
    });
});
