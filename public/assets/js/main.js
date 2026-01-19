document.addEventListener("DOMContentLoaded", () => {

    /* ================= STICKY HEADER ================= */
    const header = document.querySelector(".header");
    window.addEventListener("scroll", () => {
        if (window.scrollY > 20) {
            header.classList.add("scrolled");
        } else {
            header.classList.remove("scrolled");
        }
    });

    /* ================= MOBILE MENU ================= */
    const toggle = document.querySelector(".menu-toggle");
    const mobileMenu = document.querySelector(".mobile-menu");
    const closeBtn = document.querySelector(".mobile-menu .close");

    toggle.addEventListener("click", () => {
        mobileMenu.classList.add("active");
        document.body.style.overflow = "hidden";
    });

    closeBtn.addEventListener("click", () => {
        mobileMenu.classList.remove("active");
        document.body.style.overflow = "";
    });

    window.addEventListener("resize", () => {
        if (window.innerWidth > 992) {
            mobileMenu.classList.remove("active");
            document.body.style.overflow = "";
        }
    });

    /* ================= MOBILE DROPDOWN ================= */
    document.querySelectorAll(".mobile-toggle").forEach(toggle => {
        toggle.addEventListener("click", function (e) {
            e.preventDefault();

            const parent = this.closest("li");
            parent.classList.toggle("open");

            const icon = this.querySelector(".icon");
            if (icon) {
                icon.textContent = parent.classList.contains("open") ? "<" : ">";
            }
        });
    });

});
