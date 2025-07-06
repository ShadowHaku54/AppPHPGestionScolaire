const tip = document.getElementById("tooltip");
document.querySelectorAll(".act").forEach(btn => {
    btn.addEventListener("mouseenter", () => {
        const r = btn.getBoundingClientRect();
        tip.textContent = btn.dataset.msg;
        tip.style.display = "block";
        tip.style.top = (r.bottom + 8) + "px";
        tip.style.left = (r.left + r.width / 2) + "px";
        tip.style.transform = "translateX(-50%)";
    });
    btn.addEventListener("mouseleave", () => tip.style.display = "none")
});
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("tbody tr").forEach((r, i) => setTimeout(() => r.classList.add("loaded"), i * 90))
});