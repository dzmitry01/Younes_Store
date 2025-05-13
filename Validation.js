function Validation_login()
{
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();
    const loginError = document.getElementById("loginError");

    loginError.innerHTML="";

    if (!email && !password){
        loginError.innerHTML="Please enter your email and password";
        return false;
    } else if (!email){
        loginError.innerHTML="Please enter an email";
        return false;
    } else if (!password){
        loginError.innerHTML="Please enter a password";
        return false;
    } else {
        return true;
    }
}

function Validation_infos(event) {
    event.preventDefault();
    console.log("Validation started");

    // Get form elements
    const firstname = document.getElementById('Firstname').value;
    const lastname = document.getElementById('lastName').value;
    const age = document.getElementById('age').value;
    const wilaya = document.getElementById('wilaya').value;
    const phone = document.getElementById('phone').value;
    const email = document.getElementById('email').value;
    const address = document.getElementById('address').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const gender = document.querySelector('input[name="Sexe_Clt"]:checked')?.value;

    // Clear previous error
    const errorDiv = document.getElementById('infoError');
    errorDiv.textContent = '';

    // Validate required fields
    if (!firstname || !lastname || !age || !wilaya || !phone || !email || !address || !password || !confirmPassword || !gender) {
        errorDiv.textContent = 'All fields are required';
        return false;
    }

    // Validate age
    if (isNaN(age) || age < 18) {
        errorDiv.textContent = 'Age must be at least 18';
        return false;
    }

    // Validate email format
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        errorDiv.textContent = 'Invalid email format';
        return false;
    }

    // Validate phone number (basic validation)
    const phoneRegex = /^[0-9]{10}$/;
    if (!phoneRegex.test(phone)) {
        errorDiv.textContent = 'Phone number must be 10 digits';
        return false;
    }

    // Validate password length
    if (password.length < 6) {
        errorDiv.textContent = 'Password must be at least 6 characters long';
        return false;
    }

    // Validate password match
    if (password !== confirmPassword) {
        errorDiv.textContent = 'Passwords do not match';
        return false;
    }

    // Show confirmation modal
    const modal = document.getElementById('confirmationModal');
    const details = document.getElementById('confirmationDetails');
    
    // Populate confirmation details
    details.innerHTML = `
        <p><strong>First Name:</strong> ${firstname}</p>
        <p><strong>Last Name:</strong> ${lastname}</p>
        <p><strong>Age:</strong> ${age}</p>
        <p><strong>Wilaya:</strong> ${wilaya}</p>
        <p><strong>Phone:</strong> ${phone}</p>
        <p><strong>Email:</strong> ${email}</p>
        <p><strong>Address:</strong> ${address}</p>
        <p><strong>Gender:</strong> ${gender === 'M' ? 'Male' : 'Female'}</p>
    `;

    // Show modal
    modal.style.display = 'flex';
    return false;
}

function closeModal() {
    const modal = document.getElementById('confirmationModal');
    modal.style.display = 'none';
}

function finalSubmit() {
    const form = document.getElementById('registerForm');
    const formData = new FormData(form);

    // Show loading state
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    submitButton.textContent = 'Registering...';
    submitButton.disabled = true;

    // Send form data
    fetch('inscription.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            const messageDiv = document.getElementById('confirmationMessage');
            messageDiv.textContent = data.message;
            messageDiv.style.display = 'block';
            messageDiv.style.backgroundColor = '#e8f5e9';
            messageDiv.style.color = '#2f9e44';

            // Close modal
            closeModal();

            // Redirect after 2 seconds
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 2000);
        } else {
            // Show error message
            const errorDiv = document.getElementById('infoError');
            errorDiv.textContent = data.message;
            errorDiv.style.display = 'block';
            
            // Reset button
            submitButton.textContent = originalText;
            submitButton.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const errorDiv = document.getElementById('infoError');
        errorDiv.textContent = 'An error occurred. Please try again.';
        errorDiv.style.display = 'block';
        
        // Reset button
        submitButton.textContent = originalText;
        submitButton.disabled = false;
    });
}