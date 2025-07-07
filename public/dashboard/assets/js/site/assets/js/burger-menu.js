const cartBtn = document.getElementById("cart-btn");
const closeCartBtn = document.getElementById("close-cart-btn");
const viewMoreBtn = document.getElementById("view-more-btn");
const cartView = document.getElementById("cart-view");
const detailedCartPage = document.getElementById("detailed-cart-page");
/*
cartBtn.addEventListener("click", function () {
    cartView.classList.add("show");
});

closeCartBtn.addEventListener("click", function () {
    cartView.classList.remove("show");
});


viewMoreBtn.addEventListener("click", function () {
    cartView.classList.remove("show");
    detailedCartPage.classList.remove("d-none");
    detailedCartPage.scrollIntoView({ behavior: "smooth" });
});
*/

//! Close cart view when clicking outside of it
document.addEventListener("click", function (event) {
    if (!cartView.contains(event.target) && !cartBtn.contains(event.target)) {
        cartView.classList.remove("show");
    }
});

$(document).ready(function () {
    $("#sidebarCollapseBtn").on("click", function () {
        $("#sidebar").toggleClass("active");
    });

    $("#sidebarCollapse").on("click", function () {
        $("#sidebar").removeClass("active");
    });
});

/* !
let currentRowGallery = 0;
const rowsGallery = ['secondRowgallery', 'thirdRowgallery'];

document.getElementById('loadmoregallery').addEventListener('click', function() {
    if (currentRowGallery < rowsGallery.length) {
        document.getElementById(rowsGallery[currentRowGallery]).style.display = 'flex';
        currentRowGallery++;
        document.getElementById('showlessgallery').style.display = 'inline-block';
    }
    if (currentRowGallery === rowsGallery.length) {
        this.style.display = 'none';
        
        

    }
});

document.getElementById('showlessgallery').addEventListener('click', function() {
    if (currentRowGallery > 0) {
        currentRowGallery--;
        document.getElementById(rowsGallery[currentRowGallery]).style.display = 'none';
    }
    if (currentRowGallery < rowsGallery.length) {
        document.getElementById('loadmoregallery').style.display = 'inline-block';
    }
    if (currentRowGallery === 0) {
        this.style.display = 'none';
    }
});


let currentRowCourses = 0;
const rowsCourses = ['secondRowCourses', 'thirdRowCourses'];

document.getElementById('loadmoreCourses').addEventListener('click', function() {
    if (currentRowCourses < rowsCourses.length) {
        document.getElementById(rowsCourses[currentRowCourses]).style.display = 'flex';
        currentRowCourses++;
        document.getElementById('showlessCourses').style.display = 'inline-block';
    }
    if (currentRowCourses === rowsCourses.length) {
        this.style.display = 'none';
        

    }
});

document.getElementById('showlessCourses').addEventListener('click', function() {
    if (currentRowCourses > 0) {
        currentRowCourses--;
        document.getElementById(rowsCourses[currentRowCourses]).style.display = 'none';
    }
    if (currentRowCourses < rowsCourses.length) {
        document.getElementById('loadmoreCourses').style.display = 'inline-block';
    }
    if (currentRowCourses === 0) {
        this.style.display = 'none';
    }
});*/

document.addEventListener("DOMContentLoaded", function () {
    const loadMoreButton = document.getElementById("loadmoregallery");
    const showLessButton = document.getElementById("showlessgallery");
    const loadingDiv = document.getElementById("loading");
    const galleries = document.querySelectorAll(".row.gallery");

    let visibleGalleryCount = 1;

    function showLoading() {
        loadingDiv.style.opacity = "1";
        loadingDiv.style.visibility = "visible";
        loadingDiv.style.position = "relative";
    }

    function hideLoading() {
        loadingDiv.style.opacity = "0";
        loadingDiv.style.visibility = "hidden";
        loadingDiv.style.position = "absolute";
    }

    loadMoreButton.addEventListener("click", function () {
        showLoading();
        setTimeout(() => {
            if (visibleGalleryCount < galleries.length) {
                galleries[visibleGalleryCount].classList.add("show");
                visibleGalleryCount++;
                showLessButton.style.display = "inline-block";
            }

            if (visibleGalleryCount === galleries.length) {
                loadMoreButton.style.display = "none";
            }
            hideLoading();
        }, 1000); // Simulate loading time
    });

    showLessButton.addEventListener("click", function () {
        if (visibleGalleryCount >= 1) {
            visibleGalleryCount--;

            galleries[visibleGalleryCount].classList.remove("show");
            loadMoreButton.style.display = "inline-block";
        }

        if (visibleGalleryCount < galleries.length) {
            loadMoreButton.style.display = "inline-block";
        }
        if (visibleGalleryCount === 1) {
            showLessButton.style.display = "none";
        }
    });

    galleries[0].classList.add("show");
});

const buttons = document.querySelectorAll(".category-Btn");
const contents = document.querySelectorAll(".content");

buttons.forEach((button) => {
    button.addEventListener("click", () => {
        const contentId = button.dataset.contentId;

        // Deactivate all buttons
        buttons.forEach((b) => b.classList.remove("active"));

        // Activate clicked button
        button.classList.add("active");

        // Hide all content
        contents.forEach((container) => {
            container.style.visibility = "hidden";
            container.style.opacity = "0";
            container.style.position = "absolute";
        });

        // Show the corresponding content
        const tragetcontent = document.getElementById(contentId);
        setTimeout(() => {
            tragetcontent.style.opacity = "1";
            tragetcontent.style.visibility = "visible";
            tragetcontent.style.position = "relative";
        }, 50);
    });
});

/* courses Pagination*/

document.addEventListener("DOMContentLoaded", function () {
    var links = document.querySelectorAll(".navPag[data-page]");
    var prevLink = document.getElementById("prev");
    var nextLink = document.getElementById("next");
    var currentPage = 1;

    function showPage(page) {
        // Hide all content sections
        var sections = document.querySelectorAll(".content-section");
        sections.forEach(function (section) {
            section.classList.remove("active");
        });

        // Show the selected content section
        document.getElementById("content-" + page).classList.add("active");

        // Remove active class from all page items
        var pageItems = document.querySelectorAll(".page-item");
        pageItems.forEach(function (item) {
            item.classList.remove("active");
        });

        // Add active class to the clicked page item
        document
            .querySelector('.navPag[data-page="' + page + '"]')
            .parentElement.classList.add("active");

        // Update current page
        currentPage = page;
    }

    links.forEach(function (link) {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            var page = parseInt(this.getAttribute("data-page"));
            showPage(page);
        });
    });

    prevLink.addEventListener("click", function (e) {
        e.preventDefault();
        if (currentPage > 1) {
            showPage(currentPage - 1);
        }

        if (currentPage < 1) {
            showPage(1);
        }
    });

    nextLink.addEventListener("click", function (e) {
        e.preventDefault();
        if (currentPage <= 6) {
            // last page number changes
            showPage(currentPage + 1);
        }

        if (currentPage > 6) {
            // last page number changes
            showPage(21);
        }
    });

    // Initialize by showing the first page
    showPage(currentPage);
});

//* Show or hide the back-to-top button based on scroll position

window.addEventListener("scroll", function () {
    var backToTopButton = document.getElementById("back-to-top");
    if (window.scrollY > 300) {
        backToTopButton.style.display = "block";
    } else {
        backToTopButton.style.display = "none";
    }
});

//* Scroll back to the top when the button is clicked

document.getElementById("back-to-top").addEventListener("click", function () {
    window.scrollTo({
        top: 0,
        behavior: "smooth",
    });
});

// ? counter //

document.addEventListener("DOMContentLoaded", function () {
    const counters = document.querySelectorAll(".counter");
    counters.forEach((counter) => {
        counter.innerText = "0";
        const updateCounter = () => {
            const target = +counter.getAttribute("data-count");
            const count = +counter.innerText;
            const increment = target / 200;

            if (count < target) {
                counter.innerText = `${Math.ceil(count + increment)}`;
                setTimeout(updateCounter, 20);
            } else {
                counter.innerText = target;
            }
        };
        updateCounter();
    });
});

function handleChkboxClick(checkbox) {
    let check = document.querySelectorAll('input[type="checkbox"]');

    check.forEach((cb) => {
        if (cb !== checkbox) {
            cb.checked = false;
        }
    });

    let fileField = document.getElementById("fileField");
    if (checkbox.checked) {
        fileField.disabled = false;
    } else {
        fileField.disabled = true;
    }
}
