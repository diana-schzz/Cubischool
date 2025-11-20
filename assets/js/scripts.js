var currentYear;
var currentMonth;

document.addEventListener("DOMContentLoaded", () => {
    const today = new Date();
    currentYear = today.getFullYear();
    currentMonth = today.getMonth();

    renderCalendar();

    document.getElementById("prev")?.addEventListener("click", () => {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        renderCalendar();
    });

    document.getElementById("next")?.addEventListener("click", () => {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        renderCalendar();
    });
});

function renderCalendar() {
    const date = new Date(currentYear, currentMonth);
    const year = currentYear;
    const month = currentMonth;

    const firstDay = new Date(year, month, 1);
    const lastDay  = new Date(year, month + 1, 0);
    const daysInMonth = lastDay.getDate();
    const startDay = firstDay.getDay() === 0 ? 7 : firstDay.getDay();

    const daysContainer    = document.querySelector(".days");
    const currentDateElem  = document.querySelector(".current-date");

    if (!daysContainer) return;

    let html = "";

    // Fecha actual
    const today = new Date();
    const hoyY = today.getFullYear();
    const hoyM = today.getMonth();
    const hoyD = today.getDate();

    // Días vacíos
    for (let i = startDay - 1; i > 0; i--) html += `<li class="inactive"></li>`;

    for (let d = 1; d <= daysInMonth; d++) {

        const fecha = new Date(year, month, d);
        const dayOfWeek = fecha.getDay(); // 0 domingo, 6 sábado

        let disabled = false;

        // 1️⃣ Bloquear días pasados
        if (fecha < new Date(hoyY, hoyM, hoyD)) disabled = true;

        // 2️⃣ Bloquear sábado y domingo
        if (dayOfWeek === 0 || dayOfWeek === 6) disabled = true;

        if (disabled) {
            html += `<li class="inactive" style="color:#bbb; cursor:not-allowed;">${d}</li>`;
        } else {
            html += `<li onclick="selectDay(this, ${d})" style="cursor:pointer;">${d}</li>`;
        }
    }

    daysContainer.innerHTML = html;

    currentDateElem.innerText =
        `${date.toLocaleString("default", { month: "long" })} ${year}`;
}

function selectDay(el, day) {
    const allDays = document.querySelectorAll(".days li");
    allDays.forEach(li => li.classList.remove("active"));

    el.classList.add("active");

    const yyyy = currentYear;
    const mm   = String(currentMonth + 1).padStart(2, "0");
    const dd   = String(day).padStart(2, "0");

    const fecha = `${yyyy}-${mm}-${dd}`;

    const inputFecha = document.getElementById("fechaSeleccionadaInput");
    if (inputFecha) inputFecha.value = fecha;

    console.log("📅 Fecha seleccionada:", fecha);
}
