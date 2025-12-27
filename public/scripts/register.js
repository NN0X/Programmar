function register() {
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const confirmedPassword = document.getElementById('confirmedPassword').value;

        if (password !== confirmedPassword)
        {
                alert("Passwords do not match!");
                return;
        }

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
                        if (data.success)
                        {
                                alert('Registration successful! Please login.');
                                window.location.href = '/login';
                        }
                        else
                        {
                                alert('Registration failed: ' + data.message);
                        }
                })
                .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred during registration.');
                });
}
