function showToast(message, type = 'success')
{
        let container = document.getElementById('toast-container');
        if (!container) {
             container = document.createElement('div');
             container.id = 'toast-container';
             document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerText = message;

        container.appendChild(toast);

        setTimeout(() => {
                toast.classList.add('hiding');

                toast.addEventListener('animationend', () => {
                        toast.remove();
                });

                setTimeout(() => {
                        if (toast.parentElement) {
                                toast.remove();
                        }
                }, 500);
        }, 3000);
}
