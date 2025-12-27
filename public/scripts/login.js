function login()
{
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        const requestOptions = {
                method: 'POST',
                headers: { 'Content-Type': 'application/json; charset=UTF-8' },
                body: JSON.stringify({ email: email, password: password })
        };

        fetch('/login', requestOptions)
                .then(response => response.json())
                .then(data => {
                        if (data.success)
                        {
                                alert('Login successful!');
                                window.location.href = '/dashboard';
                        }
                        else
                        {
                                alert('Login failed: ' + data.message);
                        }
                })
                .catch(error => {
                        console.error('Error during login:', error);
                        alert('An error occurred. Please try again later.');
                });
}
