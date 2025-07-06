document.addEventListener("DOMContentLoaded", () => {
    const s = document.getElementById("start"), e = document.getElementById("end");
    const now = new Date().getFullYear();
    s.value = now;
    e.value = now + 1;
    const sync = (src) => {
        const sv = parseInt(s.value) || 0, ev = parseInt(e.value) || 0;
        if (src === "s" && e.value != sv + 1) e.value = sv + 1;
        if (src === "e" && s.value != ev - 1) s.value = ev - 1
    };
    s.addEventListener("input", () => sync("s"));
    e.addEventListener("input", () => sync("e"))
});