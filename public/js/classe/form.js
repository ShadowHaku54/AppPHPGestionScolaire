window.addEventListener("DOMContentLoaded", () => {
    const dateInputs = document.querySelectorAll('input[type="date"]');

    if (dateInputs.length >= 2) {
        const today = new Date();

        const formatDate = (date) => {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        };

        if (!dateInputs[0].value) {
            dateInputs[0].value = formatDate(today);
        }

        if (!dateInputs[1].value) {
            const plus30Days = new Date(today);
            plus30Days.setDate(today.getDate() + 30);
            dateInputs[1].value = formatDate(plus30Days);
        }
    }
});