"use strict";
/*Namespace
------------------------------------------------------- */
var blogtwist = blogtwist || {};

document.querySelectorAll('.single.wp-embed-responsive .wp-has-aspect-ratio').forEach(element => {
    if (!element.querySelector('object, embed, iframe, video')) {
        element.querySelector('.wp-block-embed__wrapper')?.style.setProperty('display', 'none');
    }
});

/* Preloader
 **-----------------------------------------------------*/
blogtwist.PreLoader = {
    init: function () {
        let preloader = document.querySelector("#wpmotif-preloader");
        if (preloader) {
            preloader.classList.add("wpmotif-preloader-exit");
            setTimeout(function () {
                preloader.style.display = "none";
            }, 1000);
        }
    },
};
/* Cursor
 **-----------------------------------------------------*/
blogtwist.Cursor = {
    init: function () {
        if (document.body.classList.contains("has-custom-cursor")) {
            const innerCursor = document.querySelector(".circle-cursor-inner"),
                outerCursor = document.querySelector(".circle-cursor-outer");
            if (!innerCursor || !outerCursor) return;
            let mouseX, mouseY = 0, magnetMode = false;

            function resetOuterCursor() {
                outerCursor.style.transition = "";
                outerCursor.style.width = "";
                outerCursor.style.height = "";
                outerCursor.style.marginLeft = "";
                outerCursor.style.marginTop = "";
                magnetMode = false;
            }

            window.onmousemove = function (event) {
                if (!magnetMode) {
                    outerCursor.style.transform = `translate(${event.clientX}px, ${event.clientY}px)`;
                }
                innerCursor.style.transform = `translate(${event.clientX}px, ${event.clientY}px)`;
                mouseX = event.clientX;
                mouseY = event.clientY;
                const target = event.target;
                if (target.tagName === "IFRAME") {
                    outerCursor.style.visibility = "hidden";
                    innerCursor.style.visibility = "hidden";
                } else {
                    outerCursor.style.visibility = "visible";
                    innerCursor.style.visibility = "visible";
                }
            };

            function addEventListenerToElements(selector, event, handler) {
                document.querySelectorAll(selector).forEach(element => {
                    element.addEventListener(event, handler);
                });
            }

            addEventListenerToElements("a, .cursor-as-pointer", "mouseenter", () => {
                innerCursor.classList.add("cursor-link-hover");
                outerCursor.classList.add("cursor-link-hover");
            });
            addEventListenerToElements("a, .cursor-as-pointer", "mouseleave", function () {
                if (!this.closest(".cursor-as-pointer")) {
                    innerCursor.classList.remove("cursor-link-hover");
                    outerCursor.classList.remove("cursor-link-hover");
                }
            });
            addEventListenerToElements("[data-cursor-class]", "mouseenter", function () {
                const cursorClass = this.getAttribute("data-cursor-class");
                if (cursorClass.includes("dark-color")) {
                    innerCursor.classList.add("dark-color");
                    outerCursor.classList.add("dark-color");
                }
                if (cursorClass.includes("cursor-link")) {
                    innerCursor.classList.add("cursor-link");
                    outerCursor.classList.add("cursor-link");
                }
            });
            addEventListenerToElements("[data-cursor-class]", "mouseleave", function () {
                const cursorClass = this.getAttribute("data-cursor-class");
                if (cursorClass.includes("dark-color")) {
                    innerCursor.classList.remove("dark-color");
                    outerCursor.classList.remove("dark-color");
                }
                if (cursorClass.includes("cursor-link")) {
                    innerCursor.classList.remove("cursor-link");
                    outerCursor.classList.remove("cursor-link");
                }
            });
            addEventListenerToElements(".cursor-magnet, .icon-button", "mouseenter", function () {
                const rect = this.getBoundingClientRect();
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                outerCursor.style.transition = "all .2s ease-out";
                outerCursor.style.transform = `translate(${rect.left}px, ${rect.top - scrollTop}px)`;
                outerCursor.style.width = `${rect.width}px`;
                outerCursor.style.height = `${rect.height}px`;
                outerCursor.style.marginLeft = "0";
                outerCursor.style.marginTop = "0";
                magnetMode = true;
            });
            addEventListenerToElements(".cursor-magnet, .icon-button", "mouseleave", resetOuterCursor);
            document.addEventListener("ohio:cursor_mouseleave", function () {
                resetOuterCursor();
                outerCursor.style.transform = innerCursor.style.transform;
                innerCursor.classList.remove("cursor-link-hover");
                outerCursor.classList.remove("cursor-link-hover");
            });
            addEventListenerToElements("iframe", "mouseenter", function () {
                outerCursor.style.visibility = "hidden";
                innerCursor.style.visibility = "hidden";
            });
            innerCursor.style.visibility = "visible";
            outerCursor.style.visibility = "visible";
        }
    }
};
/* Site Logo
 **-----------------------------------------------------*/
blogtwist.SiteLogo = {
    init: function () {
        let lastScrollTop = 0.5;
        const branding = document.querySelector('.site-title');
        const brandingSvgs = document.querySelectorAll('.site-branding-svg');

// Function to update SVG width
        const updateSvgWidth = () => {
            brandingSvgs.forEach((brandingSvg) => {
                const textElement = brandingSvg.querySelector('text[x="50%"][y="0.82em"]');
                if (textElement) {
                    const textWidth = textElement.getBBox().width;
                    const totalWidth = textWidth + 20; // Adding 20px padding
                    brandingSvg.style.width = `${totalWidth}px`;
                }
            });
        };

// Initial width update
        updateSvgWidth();

// Update width on window resize
        window.addEventListener('resize', updateSvgWidth);

    },
};


/* Display Clock
 **-----------------------------------------------------*/
blogtwist.displayClock = {
    init: function () {
        if (document.getElementsByClassName("wpmotif-display-clock").length > 0) {
            setInterval(function () {
                let currentTime = new Date();
                let hours = currentTime.getHours();
                let minutes = currentTime.getMinutes();
                let seconds = currentTime.getSeconds();
                let ampm = hours >= 12 ? "PM" : "AM";
                hours = hours % 12;
                hours = hours ? hours : 12;
                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;
                let timeString =
                    '<span class="wpmotif-clock-unit clock-unit-hours">' +
                    hours +
                    "</span>" +
                    ":" +
                    '<span class="wpmotif-clock-unit clock-unit-minute">' +
                    minutes +
                    "</span>" +
                    ":" +
                    '<span class="wpmotif-clock-unit clock-unit-seconds">' +
                    seconds +
                    "</span>" +
                    " " +
                    '<span class="wpmotif-clock-unit clock-unit-format">' +
                    ampm +
                    "</span>";
                document.getElementsByClassName(
                    "wpmotif-display-clock"
                )[0].innerHTML = timeString;
            }, 1000);
        }
    },
};


/* Tab Widget
 **-----------------------------------------------------*/
blogtwist.TabbedWidget = {
    init: function () {
        const widgetContainers = document.querySelectorAll(".wpmotif-tabbed-widget");
        widgetContainers.forEach((container) => {
            const tabs = container.querySelectorAll(
                ".tabbed-widget-header .tabbed-header-item"
            );
            const tabPanes = container.querySelectorAll(
                ".tabbed-widget-content .tabbed-content-item"
            );
            tabs.forEach((tab) => {
                tab.addEventListener("click", function (event) {
                    const tabid = this.getAttribute("tab-data");
                    tabs.forEach((tab) => tab.classList.remove("active"));
                    tabPanes.forEach((tabPane) => tabPane.classList.remove("active"));
                    this.classList.add("active");
                    container.querySelector(`.content-${tabid}`).classList.add("active");
                });
            });
        });
    },
};
/* ProgressBar
 **-----------------------------------------------------*/
blogtwist.ProgressBar = {
    init: function () {
        const progressBar = document.getElementById("progressBar");
        // Check if progressBar exists before proceeding
        if (progressBar) {
            const totalHeight = document.body.scrollHeight - window.innerHeight;
            const scrollPosition = window.scrollY;
            const scrollPercentage = (scrollPosition / totalHeight) * 100;
            progressBar.style.width = scrollPercentage + "%";
            // Update progress on scroll
            window.addEventListener("scroll", function () {
                const scrollPosition = window.scrollY;
                const scrollPercentage = (scrollPosition / totalHeight) * 100;
                progressBar.style.width = scrollPercentage + "%";
            });
        }
    },
};

/* Back To Top
 **-----------------------------------------------------*/
blogtwist.BackToTop = {
    init: function () {
        const scrollTopBtn = document.getElementById('scrollToTop');

        // Scroll to the top when button is clicked
        scrollTopBtn.onclick = () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        };
    },
};
/* ProgressBar
 **-----------------------------------------------------*/

/* Load functions at proper events
 *--------------------------------------------------*/
/**
 * Is the DOM ready?
 *
 * This implementation is coming from https://gomakethings.com/a-native-javascript-equivalent-of-jquerys-ready-method/
 *
 * @param {Function} fn Callback function to run.
 */
function blogtwistDomReady(fn) {
    if (typeof fn !== "function") {
        return;
    }
    if (
        document.readyState === "interactive" ||
        document.readyState === "complete"
    ) {
        return fn();
    }
    document.addEventListener("DOMContentLoaded", fn, false);
}

blogtwistDomReady(function () {
    blogtwist.Cursor.init();
    blogtwist.SiteLogo.init();
    blogtwist.displayClock.init();
    blogtwist.TabbedWidget.init();
    blogtwist.BackToTop.init();
});
window.addEventListener("load", function (event) {
    blogtwist.PreLoader.init();
});
window.addEventListener("scroll", function (event) {
    blogtwist.ProgressBar.init();
});