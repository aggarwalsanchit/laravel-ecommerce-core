// resources/js/sweetalert.js

import Swal from "sweetalert2";

class SweetAlert {
    // Success toast (auto-hide)
    static success(message, title = "Success!", duration = 3000) {
        Swal.fire({
            icon: "success",
            title: title,
            text: message,
            timer: duration,
            showConfirmButton: false,
            toast: true,
            position: "top-end",
            showCloseButton: true,
            customClass: {
                popup: "swal-toast",
                title: "swal-title-small",
            },
        });
    }

    // Error toast (auto-hide)
    static error(message, title = "Error!", duration = 4000) {
        Swal.fire({
            icon: "error",
            title: title,
            text: message,
            timer: duration,
            showConfirmButton: false,
            toast: true,
            position: "top-end",
            showCloseButton: true,
            customClass: {
                popup: "swal-toast",
            },
        });
    }

    // Warning toast (auto-hide)
    static warning(message, title = "Warning!", duration = 3000) {
        Swal.fire({
            icon: "warning",
            title: title,
            text: message,
            timer: duration,
            showConfirmButton: false,
            toast: true,
            position: "top-end",
            showCloseButton: true,
        });
    }

    // Info toast (auto-hide)
    static info(message, title = "Information", duration = 3000) {
        Swal.fire({
            icon: "info",
            title: title,
            text: message,
            timer: duration,
            showConfirmButton: false,
            toast: true,
            position: "top-end",
            showCloseButton: true,
        });
    }

    // Confirmation dialog
    static confirm(
        title,
        text,
        confirmText = "Yes, proceed!",
        cancelText = "Cancel",
        icon = "question",
    ) {
        return Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: "#0d6efd",
            cancelButtonColor: "#6c757d",
            confirmButtonText: confirmText,
            cancelButtonText: cancelText,
            reverseButtons: true,
        });
    }

    // Delete confirmation (danger)
    static confirmDelete(itemName = "this item") {
        return Swal.fire({
            title: "Delete Confirmation",
            text: `Are you sure you want to delete "${itemName}"? This action cannot be undone!`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel",
            reverseButtons: true,
        });
    }

    // Activate confirmation
    static confirmActivate(itemName = "this item") {
        return Swal.fire({
            title: "Activate Confirmation",
            text: `Are you sure you want to activate "${itemName}"?`,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#198754",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, activate!",
            cancelButtonText: "Cancel",
        });
    }

    // Deactivate confirmation
    static confirmDeactivate(itemName = "this item") {
        return Swal.fire({
            title: "Deactivate Confirmation",
            text: `Are you sure you want to deactivate "${itemName}"? They will not be able to login.`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#ffc107",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, deactivate!",
            cancelButtonText: "Cancel",
            reverseButtons: true,
        });
    }

    // Loading indicator
    static loading(message = "Processing...") {
        Swal.fire({
            title: message,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            },
        });
    }

    // Close loading
    static close() {
        Swal.close();
    }

    // Form validation error
    static validationError(errors) {
        let errorHtml = '<ul style="text-align: left;">';
        for (let field in errors) {
            errorHtml += `<li><strong>${field}:</strong> ${errors[field][0]}</li>`;
        }
        errorHtml += "</ul>";

        Swal.fire({
            icon: "error",
            title: "Validation Error",
            html: errorHtml,
            confirmButtonColor: "#d33",
        });
    }

    // Server error
    static serverError(message = "Something went wrong. Please try again.") {
        Swal.fire({
            icon: "error",
            title: "Server Error",
            text: message,
            confirmButtonColor: "#d33",
        });
    }

    // Bulk action confirmation
    static confirmBulkAction(action, count, itemNames = []) {
        let actionText =
            action === "activate"
                ? "activate"
                : action === "deactivate"
                  ? "deactivate"
                  : "delete";
        let actionColor =
            action === "activate"
                ? "#198754"
                : action === "deactivate"
                  ? "#ffc107"
                  : "#d33";
        let icon =
            action === "activate"
                ? "question"
                : action === "deactivate"
                  ? "warning"
                  : "error";

        let message = `Are you sure you want to ${actionText} ${count} selected user(s)?`;
        if (action === "delete") {
            message = `Are you sure you want to ${actionText} ${count} selected user(s)? This action cannot be undone!`;
        }

        let userList = "";
        if (itemNames.length > 0 && itemNames.length <= 5) {
            userList = `<br><br><strong>Selected users:</strong><br>${itemNames.join(", ")}`;
        } else if (itemNames.length > 5) {
            userList = `<br><br><strong>Selected users:</strong><br>${itemNames.slice(0, 5).join(", ")} and ${itemNames.length - 5} more...`;
        }

        return Swal.fire({
            title: `${actionText.charAt(0).toUpperCase() + actionText.slice(1)} Users`,
            html: message + userList,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: actionColor,
            cancelButtonColor: "#6c757d",
            confirmButtonText: `Yes, ${actionText}!`,
            cancelButtonText: "Cancel",
            reverseButtons: true,
        });
    }
}

// Make globally available
window.SwalAlert = SweetAlert;

// Also make individual functions available globally
window.showSuccess = SweetAlert.success.bind(SweetAlert);
window.showError = SweetAlert.error.bind(SweetAlert);
window.showWarning = SweetAlert.warning.bind(SweetAlert);
window.showInfo = SweetAlert.info.bind(SweetAlert);
window.confirmAction = SweetAlert.confirm.bind(SweetAlert);
window.confirmDelete = SweetAlert.confirmDelete.bind(SweetAlert);
window.confirmActivate = SweetAlert.confirmActivate.bind(SweetAlert);
window.confirmDeactivate = SweetAlert.confirmDeactivate.bind(SweetAlert);
window.showLoading = SweetAlert.loading.bind(SweetAlert);
window.closeLoading = SweetAlert.close.bind(SweetAlert);
window.showValidationError = SweetAlert.validationError.bind(SweetAlert);
window.showServerError = SweetAlert.serverError.bind(SweetAlert);
window.confirmBulkAction = SweetAlert.confirmBulkAction.bind(SweetAlert);

export default SweetAlert;
