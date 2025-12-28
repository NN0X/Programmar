function showToast(message, type = 'success')
{
        const container = document.getElementById('toast-container');
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

function login()
{
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const button = document.querySelector('button[type="submit"]');

        button.disabled = true;
        button.innerText = "Logging in...";

        const requestOptions = {
                method: 'POST',
                headers: { 'Content-Type': 'application/json; charset=UTF-8' },
                body: JSON.stringify({ email: email, password: password })
        };

        fetch('/login', requestOptions)
                .then(response => response.json())
                .then(data => {
                        if (data.success) {
                                showToast('Login successful! Redirecting...', 'success');
                                setTimeout(() => {
                                        window.location.href = '/dashboard';
                                }, 1000);
                        } else {
                                showToast('Login failed: ' + data.message, 'error');
                                button.disabled = false;
                                button.innerText = "Log In";
                        }
                })
                .catch(error => {
                        console.error('Error during login:', error);
                        showToast('An error occurred. Please try again later.', 'error');
                        button.disabled = false;
                        button.innerText = "Log In";
                });
}
