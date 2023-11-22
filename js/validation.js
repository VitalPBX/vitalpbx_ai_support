// Initialize JustValidate on the form with the ID of 'signup'.
const validation = new JustValidate("#signup");

// Chain multiple field validation rules.
validation
    // Add validation for the 'name' field.
    .addField("#name", [
        {
            rule: "required" // The 'name' field is required.
        }
    ])  
    // Add validation for the 'email' field.
    .addField("#email", [
        {
            rule: "required" // The 'email' field is required.
        },
        {
            rule: "email" // The input must be a valid email format.
        },
        {
            // Custom validator function for checking email availability.
            validator: (value) => () => {
                // Make an asynchronous HTTP request to 'validate-email.php'.
                return fetch("validate-email.php?email=" + encodeURIComponent(value))
                        .then(function(response) {
                            // Parse the JSON response.
                            return response.json();
                        })
                        .then(function(json) {
                            // Return the availability status from the response.
                            return json.available;
                        });
            },
            errorMessage: "email already taken" // Error message if the email is already taken.
        }
    ])  
    // Add validation for the 'password' field.
    .addField("#password", [
        {
            rule: "required" // The 'password' field is required.
        },
        {
            rule: "password" // The input must follow the password format (defined by JustValidate).
        }
    ])  
    // Add validation for the 'password_confirmation' field.
    .addField("#password_confirmation", [
        {
            // Custom validator function for password confirmation.
            validator: (value, fields) => {
                // Check if the confirmation password matches the original password.
                return value === fields["#password"].elem.value;
            },
            errorMessage: "Passwords should match" // Error message if passwords do not match.
        }
    ])  
    // Event handler for successful validation.
    .onSuccess((event) => {
        // Submit the form if all validations pass.
        document.getElementById("signup").submit();
    });
