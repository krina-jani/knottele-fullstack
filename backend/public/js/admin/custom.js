// assets/js/custom.js

document.addEventListener("DOMContentLoaded", function () {

    // ============================================
    // 1️⃣ TOASTR SETUP
    // ============================================
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: "3000"
    };

    // ============================================
    // 2️⃣ SIDEBAR ELEMENTS
    // ============================================
    const sidebar = document.getElementById("sidebar");

    // Fix Tabulator layout when sidebar changes
    if (sidebar) {
        sidebar.addEventListener("mouseenter", function () {
            if (window.innerWidth >= 768) {
                setTimeout(() => fixTabulatorLayout(), 120);
            }
        });

        sidebar.addEventListener("mouseleave", function () {
            if (window.innerWidth >= 768) {
                setTimeout(() => fixTabulatorLayout(), 120);
            }
        });
    }

    const sidebarToggle = document.getElementById("sidebarToggle");
    const sidebarClose = document.getElementById("sidebarClose");
    const sidebarOverlay = document.getElementById("sidebarOverlay");
    const sidebarToggleMode = document.getElementById("sidebarToggleMode");
    const mainContent = document.getElementById("main-content");

    // Safety check
    if (!sidebar) return;

    // ============================================
    // 3️⃣ MOBILE SIDEBAR OPEN
    // ============================================
    sidebarToggle.addEventListener('click', function () {
        sidebar.classList.remove('-translate-x-full');
        sidebarOverlay.classList.remove('hidden');
        fixTabulatorLayout();
    });

    sidebarClose.addEventListener('click', function () {
        sidebar.classList.add('-translate-x-full');
        sidebarOverlay.classList.add('hidden');
        fixTabulatorLayout();
    });

    sidebarOverlay.addEventListener('click', function () {
        sidebar.classList.add('-translate-x-full');
        sidebarOverlay.classList.add('hidden');
        fixTabulatorLayout();
    });

    // ============================================
    // 5️⃣ DESKTOP SIDEBAR EXPAND / COLLAPSE (Persistent)
    // ============================================
    if (sidebarToggleMode) {
        let savedState = localStorage.getItem("sidebarState") || "collapsed";
        applySidebarMode(savedState);

        sidebarToggleMode.addEventListener("click", function () {
            if (window.innerWidth < 768) return; // disable on mobile

            let newState = sidebar.classList.contains("sidebar-expanded")
                ? "collapsed"
                : "expanded";

            applySidebarMode(newState);
            localStorage.setItem("sidebarState", newState);
            fixTabulatorLayout(); // <--- Fix Tabulator layout
        });
    }

    function applySidebarMode(state) {
        if (state === "expanded") {
            sidebar.classList.add("sidebar-expanded");
            sidebar.classList.remove("sidebar-collapsed");
            if (sidebarToggleMode) sidebarToggleMode.textContent = "Collapse";
        } else {
            sidebar.classList.add("sidebar-collapsed");
            sidebar.classList.remove("sidebar-expanded");
            if (sidebarToggleMode) sidebarToggleMode.textContent = "Expand";
        }
    }

    // ============================================
    // 6️⃣ WINDOW RESIZE HANDLING
    // ============================================
    window.addEventListener("resize", () => {
        if (window.innerWidth >= 768) {
            sidebar.classList.remove("-translate-x-full");
            sidebarOverlay.classList.add("hidden");
            document.body.style.overflow = "";
        } else {
            sidebar.classList.add("-translate-x-full");
        }
        fixTabulatorLayout();
    });

    // ============================================
    // 7️⃣ STATUS TOGGLE SWITCHES
    // ============================================
    document.addEventListener("change", function (e) {
        if (!e.target.classList.contains("toggle-status")) return;

        const productId = e.target.dataset.id;
        const isActive = e.target.checked;

        setTimeout(() => {
            toastr.success(`Product ${isActive ? "activated" : "deactivated"} successfully!`);
        }, 300);
    });

    // ============================================
    // 8️⃣ SIDEBAR SUBMENU TOGGLE (Prevent redirect)
    // ============================================
    const sidebarNav = document.querySelector("#sidebar nav");
    
    // Restore sidebar scroll position on page load
    if (sidebarNav) {
        const savedScroll = sessionStorage.getItem("sidebarScrollPos");
        if (savedScroll) {
            sidebarNav.scrollTop = parseInt(savedScroll, 10);
        } else {
            // Alternatively, scroll the active item into view if no scroll position is saved
            const activeItem = sidebarNav.querySelector(".border-red-500");
            if (activeItem) {
                activeItem.scrollIntoView({ block: "center" });
            }
        }

        // Save scroll position before unloading the page
        window.addEventListener("beforeunload", () => {
            sessionStorage.setItem("sidebarScrollPos", sidebarNav.scrollTop);
        });
    }

    document.querySelectorAll("#sidebar a.parent-link").forEach(link => {
        link.addEventListener("click", function (e) {
            const parent = this.closest(".relative");
            const submenu = parent.querySelector(".submenu");

            if (submenu) {
                e.preventDefault();
                
                // Close other open submenus
                if (!parent.classList.contains("submenu-open")) {
                    document.querySelectorAll("#sidebar .relative.submenu-open").forEach(openParent => {
                        openParent.classList.remove("submenu-open");
                    });
                }

                parent.classList.toggle("submenu-open");
            }
        });
    });

    // ============================================
    // 9️⃣ ADMIN MENU (3 DOT MENU) FIX
    // ============================================
    document.querySelectorAll('.admin-menu-toggle').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            const menu = this.parentElement.querySelector('.absolute');
            menu.classList.toggle('hidden');
        });
    });

    // Close menu on outside click
    document.addEventListener("click", () => {
        document.querySelectorAll(".admin-menu").forEach(menu => {
            menu.classList.add("hidden");
        });
    });

});

// ============================================
// 🔟 FIX TABULATOR LAYOUT FUNCTION
// ============================================

function fixTabulatorLayout() {
    setTimeout(() => {
        // Redraw all Tabulator tables on the page
        if (typeof Tabulator !== 'undefined') {
            // Check for global table instances
            if (window.productsTable && typeof window.productsTable.redraw === "function") {
                window.productsTable.redraw(true);
            }

            if (window.categoriesTable) {
                window.categoriesTable.redraw(true);
            }
            if (window.usersTable) {
                window.usersTable.redraw(true);
            }
            if (window.ordersTable) {
                window.ordersTable.redraw(true);
            }
            // Add more table instances as needed
        }
    }, 350);
}