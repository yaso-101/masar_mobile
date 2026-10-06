// Account sidebar (opened from the ☰ button in the top-right corner)

function setSidebarOpen(open) {
    const sidebar = document.getElementById("sidebar");
    const backdrop = document.getElementById("sidebar-backdrop");
    const toggle = document.getElementById("sidebar-toggle");
    if (!sidebar || !backdrop) return; // Not logged in, so no sidebar on this page

    // Slide the panel in/out ('invisible' also stops hidden links from being tabbed to)
    sidebar.classList.toggle("translate-x-full", !open);
    sidebar.classList.toggle("invisible", !open);

    // Fade the dark backdrop in/out
    backdrop.classList.toggle("opacity-0", !open);
    backdrop.classList.toggle("pointer-events-none", !open);

    if (toggle) toggle.setAttribute("aria-expanded", open ? "true" : "false");
}

window.openSidebar = function () {
    setSidebarOpen(true);
};

window.closeSidebar = function () {
    setSidebarOpen(false);
};

// Close with the Escape key
document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") window.closeSidebar();
});
