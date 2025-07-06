const header = document.getElementById("mainHeader");
const userArea = document.getElementById("userArea");
window.addEventListener("scroll", () => {
    window.scrollY > 40 ? header.classList.add("scrolled") : header.classList.remove("scrolled")
});
userArea.addEventListener("click", e => {
    userArea.classList.toggle("open");
    e.stopPropagation()
});
document.addEventListener("click", () => userArea.classList.remove("open"));