//dropdown

document.addEventListener("DOMContentLoaded", () => {

    const dropdownBtn = document.querySelector('.dropdown-button');

    if (dropdownBtn) {

        dropdownBtn.addEventListener('click', showDropDownContent);

        dropdownBtn.addEventListener('mouseenter', showDropDownContent);


        function showDropDownContent(e) {
            e.stopPropagation();
            //show when 
            const dropdownContent = document.querySelector('.dropdown-content');

            dropdownContent.classList.toggle("show");

            dropdownContent.style.bottom = `-${dropdownContent.offsetHeight - 5}px`;

            //close when clicking outside
            document.addEventListener("click", (e) => {
                e.stopPropagation();
                if (dropdownContent.classList.contains("show")) dropdownContent.classList.remove("show");
            })
        }


    }

})

