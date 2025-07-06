document.addEventListener("DOMContentLoaded", () => {
    const rows = document.querySelectorAll("tbody tr");
    rows.forEach((r, i) => {
    setTimeout(() => {
    r.classList.add("loaded")
}, i * 90)
})
});