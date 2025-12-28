document.addEventListener('DOMContentLoaded', function() {
        const resetForm = document.getElementById('reset-form');
        const deleteForm = document.getElementById('delete-form');

        if (resetForm) {
                resetForm.addEventListener('submit', function(event) {
                        const message = 'Are you sure you want to reset your course progress? Your RAM will remain unchanged.';
                        if (!confirm(message)) {
                                event.preventDefault();
                        }
                });
        }

        if (deleteForm) {
                deleteForm.addEventListener('submit', function(event) {
                        const message = 'Are you sure you want to delete your account? This action is permanent.';
                        if (!confirm(message)) {
                                event.preventDefault();
                        }
                });
        }
});
