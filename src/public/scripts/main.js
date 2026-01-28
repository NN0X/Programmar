function showToast(message, type = 'success')
{
        let container = document.getElementById('toast-container');
        if(!container) {
             container = document.createElement('div');
             container.id = 'toast-container';
             document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerText = message;

        container.appendChild(toast);

        setTimeout(() => {
                toast.style.animation = 'fadeOut 0.3s ease-in forwards';
                toast.addEventListener('animationend', () => {
                        toast.remove();
                });
        }, 3000);
}
