document.addEventListener("DOMContentLoaded", () => {

    const classHidden   = document.getElementById("selectedClassId");
    const studentHidden = document.getElementById("selectedStudentId");

    document.querySelectorAll(".remove").forEach(btn => {
        btn.addEventListener("click", () => {
            const section   = btn.dataset.remove;
            const fieldWrap = document.querySelector(`[data-section="${section}"]`);
            const hidden    = section === "class" ? classHidden : studentHidden;

            hidden.value = "";

            fieldWrap.querySelector(".content").remove();

            const ph = document.createElement("button");
            ph.type  = "submit";
            ph.name  = "overlay";
            ph.value = section;
            ph.className = "placeholder-card content";
            ph.textContent = section === "class" ? "Sélectionner une classe" : "Sélectionner un étudiant";
            fieldWrap.insertBefore(ph, fieldWrap.querySelector(".trigger"));
        });
    });

});
