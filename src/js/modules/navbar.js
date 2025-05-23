//dropdown

document.addEventListener("DOMContentLoaded", () => {

    redirectHome();

    startDropdown();

    setTopPadding();

    //redirect to home
    function redirectHome() {
        // redirect to home
        const redirectHome = document.querySelector('#redirect-home') || null;

        if (redirectHome) {
            setTimeout(() => {
                window.location.href = '/';
            }, 15000)
        }
    }

    //dropdown menu logic
    function startDropdown() {
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

    }

    //set padding for root element
    //root element to padding depending on the navbar height
    function setTopPadding() {

        const root = document.querySelector('.root') || null;
        const navBar = document.querySelector('.navbar') || null;

        if (root && navBar) {
            root.style.paddingTop = `${navBar.offsetHeight}px`;
        }
    }


})



