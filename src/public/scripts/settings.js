const resetForm = document.getElementById('reset-form');
const deleteForm = document.getElementById('delete-form');

const modal = document.getElementById('confirmation-modal');
const modalTitle = document.getElementById('modal-title');
const modalMessage = document.getElementById('modal-message');
const confirmBtn = document.getElementById('modal-confirm');
const cancelBtn = document.getElementById('modal-cancel');

let pendingForm = null;

function showModal(title, message, formToSubmit, confirmBtnClass)
{
        modalTitle.textContent = title;
        modalMessage.textContent = message;
        pendingForm = formToSubmit;
        confirmBtn.className = confirmBtnClass;
        modal.classList.add('active');
}

function hideModal()
{
        modal.classList.remove('active');
        pendingForm = null;
}

if (resetForm)
{
        resetForm.addEventListener('submit', function(event) {
                event.preventDefault();
                showModal(
                        'Reset Progress?',
                        'Are you sure you want to reset your course progress? Your RAM will remain unchanged.',
                        resetForm,
                        'btn-warning'
                );
        });
}

if (deleteForm)
{
        deleteForm.addEventListener('submit', function(event) {
                event.preventDefault();
                showModal(
                        'Delete Account?',
                        'Are you sure you want to delete your account? This action is permanent and cannot be undone.',
                        deleteForm,
                        'btn-danger'
                );
        });
}

confirmBtn.addEventListener('click', function()
{
        if (pendingForm)
        {
                if (pendingForm === resetForm || pendingForm === deleteForm)
                {
                        sessionStorage.clear();
                }

                pendingForm.submit();
        }
        hideModal();
});

cancelBtn.addEventListener('click', function()
{
        hideModal();
});

modal.addEventListener('click', function(event)
{
        if (event.target === modal)
        {
                hideModal();
        }
});
