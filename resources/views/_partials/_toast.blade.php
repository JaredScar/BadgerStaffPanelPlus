<div class="toast-container position-absolute end-0 mt-3 mx-3" id="toast_container">
    <div class="toast d-none bg-warning text-white" id="warning_toast" data-bs-autohide="true" data-bs-delay="3000">
        <div class="toast-header bg-warning text-white">
            <strong class="me-auto"><i class="fa-solid fa-triangle-exclamation"></i> Warning</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body" id="warning_toast_body">
        </div>
    </div>

    <div class="toast d-none bg-danger text-white" id="error_toast" data-bs-autohide="true" data-bs-delay="3000">
        <div class="toast-header bg-danger text-white">
            <strong class="me-auto"><i class="fa-solid fa-bomb"></i> Error Encountered</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body" id="error_toast_body">
        </div>
    </div>

    <div class="toast d-none bg-success text-white" id="success_toast" data-bs-autohide="true" data-bs-delay="3000">
        <div class="toast-header bg-success text-white">
            <strong class="me-auto"><i class="fa-solid fa-circle-check"></i> Success</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body" id="success_toast_body">
        </div>
    </div>
</div>

<script>
    function getToastBody(toastId) {
        let toast = document.getElementById(toastId);
        if (toast) {
            return toast.querySelector('.toast-body');
        }
        return null;
    }
    function showToast(toastId, msg) {
        let toast = document.getElementById(toastId);
        let toastBody = getToastBody(toastId);
        if (toast && toastBody) {
            toastBody.innerHTML = msg;
            toast.classList.remove('d-none');
            let bsToast = new bootstrap.Toast(toast);
            bsToast.show();
        }
    }
    function hideToast(toastId, msg) {
        let toast = document.getElementById(toastId);
        if (toast) {
            toast.classList.add('d-none'); // Hide the toast
        }
    }
</script>
