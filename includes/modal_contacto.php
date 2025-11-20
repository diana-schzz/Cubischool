<div id="modalRetro" class="modal">
    <div class="modal-box">
            
        <button class="close-btn" id="closeModal">&times;</button>

        <h2 class="modal-title">Contáctanos</h2>

        <form action="/cubi/dashboards/alumno/retroalimentacion.php" method="post" class="modal-form">
            
            <label>Correo institucional:</label>
            <input type="email" name="correoInstitucional" value="<?= htmlspecialchars($_SESSION['correoInstitucional'] ?? '') ?>" readonly>

            <label>Nombre completo:</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($_SESSION['nombre'] ?? '') ?>" readonly>

            <label>ID IEST:</label>
            <input type="number" name="idIest" value="<?= htmlspecialchars($_SESSION['idIest'] ?? '') ?>" readonly>

            <label>Mensaje:</label>
            <textarea name="mensaje" required></textarea>

            <div class="modal-buttons">
                <button type="button" class="btn-cancel" id="btnCancelModal">Cancelar</button>
                <button type="submit" class="btn-save">Enviar</button>
            </div>

        </form>

    </div>
</div>
