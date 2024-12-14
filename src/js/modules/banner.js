document.addEventListener("DOMContentLoaded", () => {

    const banner = document.querySelector('.banner');

    const classes = ['img-1', 'img-2', 'img-3', 'img-4', 'img-5', 'img-6'];

    let currentIndex = 0;

    setInterval(() => {

        // remove previous class
        banner.classList.remove(classes[currentIndex]);

        currentIndex = (currentIndex + 1) % classes.length;

        banner.classList.add(classes[currentIndex]);

    }, 15000); //change every 15s
});
