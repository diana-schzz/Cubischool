document.addEventListener('DOMContentLoaded', function () { 

    const btnVer        = document.getElementById('verDisponibilidad');
    const inputPersonas = document.getElementById('numPersonas');
    const panelOpciones = document.getElementById('opcionesReserva');
    const selectCub     = document.getElementById('selectCubiculo');
    const listaHoras    = document.getElementById('listaHorarios');
    const inputFecha    = document.getElementById('fechaSeleccionadaInput');
    const inputNum      = document.getElementById('numPersonasInput');
    const inputHora     = document.getElementById('horaSeleccionadaInput');

    if (!btnVer) return;

    function obtenerFechaCalendario() {
        const diaActivo = document.querySelector('.days li.active');
        if (!diaActivo) return null;

        const year  = window.currentYear;
        const month = window.currentMonth + 1;

        const dia = diaActivo.textContent.trim().padStart(2, '0');
        const mes = String(month).padStart(2, '0');

        return `${year}-${mes}-${dia}`;
    }

    btnVer.addEventListener('click', function (e) {
        e.preventDefault();

        const personas = inputPersonas.value.trim();
        let fecha = inputFecha.value.trim();

        if (!fecha) {
            fecha = obtenerFechaCalendario();
            inputFecha.value = fecha || "";
        }

        if (!personas || !fecha) {
            alert("Por favor completa número de personas y fecha.");
            return;
        }

        inputNum.value = personas;
        panelOpciones.classList.remove('hidden');
        btnVer.style.display = "none";

        fetch(`../../api/obtener_cubiculos.php?personas=${personas}&fecha=${fecha}`)
            .then(r => r.json())
            .then(data => {

                selectCub.innerHTML = '<option value="">Selecciona un cubículo...</option>';

                if (data.status !== "ok" || data.cubiculos.length === 0) {
                    selectCub.innerHTML += `<option value="">No hay cubículos disponibles</option>`;
                    return;
                }

                data.cubiculos.forEach(c => {
                    selectCub.innerHTML += `
                        <option value="${c.numCubiculo}">
                            Cubículo ${c.numCubiculo} (Capacidad: ${c.capacidad})
                        </option>`;
                });
            });
    });

    selectCub.addEventListener('change', function () {

        const numCubiculo = this.value;
        const fecha = inputFecha.value.trim();

        listaHoras.innerHTML = "";

        if (!numCubiculo || !fecha) return;

        fetch(`../../api/horarios_disponibles.php?numCubiculo=${numCubiculo}&fecha=${fecha}`)
            .then(r => r.json())
            .then(data => {

                if (data.status !== "ok") {
                    listaHoras.innerHTML = "<p style='color:red'>Error al cargar horarios</p>";
                    return;
                }

                inputHora.value = "";

                data.horarios_disponibles.forEach(h => {
                    const btn = document.createElement("button");
                    btn.type = "button";
                    btn.className = "time-slot";
                    btn.textContent = h;

                    btn.addEventListener("click", () => {
                        document.querySelectorAll(".time-slot.selected")
                            .forEach(b => b.classList.remove("selected"));

                        btn.classList.add("selected");
                        inputHora.value = h;
                    });

                    listaHoras.appendChild(btn);
                });

                data.horarios_ocupados.forEach(h => {
                    const btn = document.createElement("button");
                    btn.type = "button";
                    btn.className = "time-slot disabled";
                    btn.textContent = h;
                    btn.disabled = true;
                    listaHoras.appendChild(btn);
                });
            })
            .catch(err => {
                console.error("Error:", err);
                listaHoras.innerHTML = "<p style='color:red'>Error al cargar horarios</p>";
            });
    });
});
