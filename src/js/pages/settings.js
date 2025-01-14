document.addEventListener("DOMContentLoaded", () => {

    getBtn();
});

function getBtn() {
    const dropAccountBtn = document.getElementById("dropAccount-btn") || null;
    if (!dropAccountBtn) return;

    dropAccountBtn.addEventListener("click", dropAccount);
}

function dropAccount() {
    // show modal
    generateModal();
}

function generateModal() {
    const body = document.body;

    const modal = document.createElement("DIV")
    modal.classList.add("modal");
    modal.classList.add('show');
    modal.id = "modal";

    // btn to close the modal
    const closeModalBtn = document.createElement("BUTTON");
    closeModalBtn.classList.add("close-modal");
    closeModalBtn.textContent = "x";
    closeModalBtn.addEventListener("click", deleteModal);
    // title
    const title = document.createElement("H3");
    title.textContent = "¿Está seguro de que desea borrar la cuenta?";
    // drop account btn
    const deleteAccountBtn = document.createElement("BUTTON");
    deleteAccountBtn.classList.add("update-button");
    deleteAccountBtn.classList.add("delete-button");
    deleteAccountBtn.textContent = "Elimnar cuenta";
    deleteAccountBtn.addEventListener("click", deleteAccount);

    // modal content
    const modalContent = document.createElement("DIV");
    modalContent.classList.add("modal-content");
    modalContent.append(closeModalBtn, title, deleteAccountBtn)


    modal.appendChild(modalContent);

    // Close when clicking outside
    modal.addEventListener('click', (event) => {
        if (event.target === modal) deleteModal();
    });

    // append model
    body.appendChild(modal);


    function deleteModal() {
        modal.remove();
    }
}


async function deleteAccount() {
    const URL = `${location.origin}/home/delete-account`;
    const result = await fetch(URL, { method: "POST" });
    const data = await result.json();
    if (data.result) {
        window.location = "/logout";
    } else {
        // send to /home
        window.location = "/home";
    }
}