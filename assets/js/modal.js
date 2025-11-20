document.addEventListener("DOMContentLoaded", function () {

    const open   = document.getElementById("openModalSidebar");
    const modal  = document.getElementById("modalRetro");
    const close  = document.getElementById("closeModal");
    const cancel = document.getElementById("btnCancelModal");

    if (!modal || !open) return;

    open.addEventListener("click", function (e) {
        e.preventDefault();
        modal.style.display = "flex";
    });

    if (close) {
        close.addEventListener("click", function () {
            modal.style.display = "none";
        });
    }

    if (cancel) {
        cancel.addEventListener("click", function () {
            modal.style.display = "none";
        });
    }

    window.addEventListener("click", function (e) {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });

});
