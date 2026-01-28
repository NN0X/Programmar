function register()
{
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const confirmedPassword = document.getElementById('confirmedPassword').value;
        const button = document.querySelector('button[type="submit"]');

        if (password !== confirmedPassword) {
                showToast("Passwords do not match!", 'error');
                return;
        }

        button.disabled = true;
        button.innerText = "Signing up...";

        const requestOptions = {
                method: 'POST',
                headers: { 'Content-Type': 'application/json; charset=UTF-8' },
                body: JSON.stringify({
                        email: email,
                        password: password,
                        confirmedPassword: confirmedPassword 
                })
        };

        fetch('/register', requestOptions)
                .then(response => response.json())
                .then(data => {
                        if (data.success) {
                                showToast('Registration successful! Redirecting to login...', 'success');
                                setTimeout(() => {
                                        window.location.href = '/login';
                                }, 1500);
                        } else {
                                showToast('Registration failed: ' + data.message, 'error');
                                button.disabled = false;
                                button.innerText = "Sign Up";
                        }
                })
                .catch(error => {
                        console.error('Error:', error);
                        showToast('An error occurred during registration.', 'error');
                        button.disabled = false;
                        button.innerText = "Sign Up";
                });
}
