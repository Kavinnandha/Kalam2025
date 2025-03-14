<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration</title>
    <?php include '../header/links.php';
    include '../header/navbar_styles.php'
    ?>
    <style>
        .fire-gradient {
            background: linear-gradient(135deg, #ff4500 0%, #ff8c00 50%, #ffd700 100%);
            background-size: 400% 400%;
            animation: fireFlow 15s ease infinite;
        }

        @keyframes fireFlow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .flame {
            position: absolute;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle at 50% 0%, #ffd700, #ff4500, transparent 60%);
            filter: blur(8px);
            opacity: 0.6;
            animation: flameFlicker 3s ease-in-out infinite alternate;
        }

        @keyframes flameFlicker {
            0%, 100% { transform: scale(1) rotate(0deg); opacity: 0.6; }
            50% { transform: scale(1.1) rotate(5deg); opacity: 0.8; }
        }

        .ember {
            position: absolute;
            width: 4px;
            height: 4px;
            background: #ffd700;
            border-radius: 50%;
            filter: blur(1px);
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0) rotate(0deg); opacity: 1; }
            100% { transform: translateY(-100px) rotate(360deg); opacity: 0; }
        }

        .slide-enter {
            animation: slideIn 0.5s ease-out forwards;
        }

        .slide-exit {
            animation: slideOut 0.5s ease-in forwards;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(-50px);
            }
        }

        /* Error message styles */
        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: none;
        }

        .form-group {
            position: relative;
        }

        .has-error input {
            border-color: #ef4444 !important;
        }

        /* Success message styles */
        .success-message {
            background-color: #10b981;
            color: white;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            display: none;
        }
    </style>
</head>

<body class="bg-black pb-16">
    <?php include '../header/navbar.php'; ?>

    <div class="min-h-screen pt-16 px-4 sm:px-6 lg:px-8 fire-gradient relative overflow-hidden">
        <!-- Animated flames -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="flame" style="left: 10%; animation-delay: 0s;"></div>
            <div class="flame" style="left: 30%; animation-delay: 0.5s;"></div>
            <div class="flame" style="left: 50%; animation-delay: 1s;"></div>
            <div class="flame" style="left: 70%; animation-delay: 1.5s;"></div>
            <div class="flame" style="left: 90%; animation-delay: 2s;"></div>
        </div>

        <!-- Floating embers -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="ember" style="left: 20%; animation-delay: 0s;"></div>
            <div class="ember" style="left: 40%; animation-delay: 1s;"></div>
            <div class="ember" style="left: 60%; animation-delay: 2s;"></div>
            <div class="ember" style="left: 80%; animation-delay: 3s;"></div>
        </div>

        <div class="max-w-7xl mx-auto py-12 flex flex-col lg:flex-row items-center justify-between gap-12">
            <!-- Left side content -->
            <div class="lg:w-5/12 text-white space-y-8 text-center lg:text-left relative z-10">
                <h1 class="text-5xl font-extrabold tracking-tight sm:text-6xl">
                    Ignite Your Journey
                </h1>
                <p class="text-xl text-gray-100 leading-relaxed">
                    Unleash your potential! Register now and be part of something extraordinary.
                </p>
                <div class="text-center lg:text-left">
                    <p class="text-gray-100 mb-4">
                        Already registered?
                    </p>
                    <a href="login.php" class="inline-block py-4 px-8 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white rounded-xl font-medium transform transition-all duration-300 hover:-translate-y-1 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        Log in here
                    </a>
                </div>
            </div>

            <!-- Right side form -->
            <div class="lg:w-6/12 w-full relative z-10">
                <div class="bg-white p-8 rounded-2xl shadow-2xl">
                    <!-- Success message container -->
                    <div id="formSuccessMessage" class="success-message">
                        Registration successful! Redirecting to your dashboard...
                    </div>

                    <form id="registrationForm" action="registration_process.php" method="POST" class="space-y-6">
                        <!-- Step 1 -->
                        <div id="step1" class="space-y-6">
                            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Step 1: Basic Info</h2>

                            <div class="space-y-4">
                                <div class="form-group">
                                    <input type="text" name="name" placeholder="Full Name" required
                                        class="w-full px-5 py-4 bg-gray-50 rounded-xl border-2 border-orange-200 text-gray-900 placeholder-gray-500 focus:border-orange-500 focus:ring-orange-500 transition-colors duration-300">
                                    <div id="nameError" class="error-message">Please enter your full name</div>
                                </div>

                                <div class="form-group">
                                    <input type="email" name="email" placeholder="Email Address" required
                                        class="w-full px-5 py-4 bg-gray-50 rounded-xl border-2 border-orange-200 text-gray-900 placeholder-gray-500 focus:border-orange-500 focus:ring-orange-500 transition-colors duration-300">
                                    <div id="emailError" class="error-message">Please enter a valid email address</div>
                                </div>

                                <div class="form-group">
                                    <input type="tel" name="phone" placeholder="Phone Number" required
                                        class="w-full px-5 py-4 bg-gray-50 rounded-xl border-2 border-orange-200 text-gray-900 placeholder-gray-500 focus:border-orange-500 focus:ring-orange-500 transition-colors duration-300">
                                    <div id="phoneError" class="error-message">Please enter a valid phone number (at least 10 digits)</div>
                                </div>
                            </div>

                            <button type="button" onclick="nextStep()"
                                class="w-full py-4 px-6 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white rounded-xl font-medium transform transition-all duration-300 hover:-translate-y-1 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                Next Step
                            </button>
                        </div>

                        <!-- Step 2 -->
                        <div id="step2" class="space-y-6 hidden">
                            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Step 2: Additional Info</h2>

                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="form-group">
                                        <input type="text" name="college_id" placeholder="College" required
                                            class="px-5 py-4 bg-gray-50 rounded-xl border-2 border-orange-200 text-gray-900 placeholder-gray-500 focus:border-orange-500 focus:ring-orange-500 transition-colors duration-300">
                                        <div id="collegeError" class="error-message">Please enter your college</div>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" name="department" placeholder="Department" required
                                            class="px-5 py-4 bg-gray-50 rounded-xl border-2 border-orange-200 text-gray-900 placeholder-gray-500 focus:border-orange-500 focus:ring-orange-500 transition-colors duration-300">
                                        <div id="departmentError" class="error-message">Please enter your department</div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input type="password" name="password" placeholder="Password" required
                                        class="w-full px-5 py-4 bg-gray-50 rounded-xl border-2 border-orange-200 text-gray-900 placeholder-gray-500 focus:border-orange-500 focus:ring-orange-500 transition-colors duration-300">
                                    <div id="passwordError" class="error-message">Password must be at least 6 characters</div>
                                </div>

                                <div class="form-group">
                                    <input type="password" name="confirm_password" placeholder="Confirm Password" required
                                        class="w-full px-5 py-4 bg-gray-50 rounded-xl border-2 border-orange-200 text-gray-900 placeholder-gray-500 focus:border-orange-500 focus:ring-orange-500 transition-colors duration-300">
                                    <div id="confirmPasswordError" class="error-message">Passwords do not match</div>
                                </div>
                            </div>

                            <!-- Form-level error message for general errors -->
                            <div id="formError" class="bg-red-100 text-red-700 p-4 rounded-xl hidden">
                                <p>There was an error with your submission. Please check all fields and try again.</p>
                            </div>

                            <div class="flex gap-4">
                                <button type="button" onclick="previousStep()"
                                    class="w-1/3 py-4 px-6 text-orange-600 bg-orange-50 hover:bg-orange-100 rounded-xl font-medium transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                    Back
                                </button>

                                <button type="submit"
                                    class="w-2/3 py-4 px-6 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white rounded-xl font-medium transform transition-all duration-300 hover:-translate-y-1 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                    Complete Registration
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-6 text-center">
                        <p class="text-gray-600">
                            Already registered?
                            <a href="login.php" class="text-orange-600 hover:text-orange-700 font-medium transition-colors duration-300">
                                Log in now
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function createEmber() {
            const ember = document.createElement('div');
            ember.className = 'ember';
            ember.style.left = Math.random() * 100 + '%';
            document.querySelector('.fire-gradient').appendChild(ember);
            
            setTimeout(() => {
                ember.remove();
            }, 4000);
        }

        setInterval(createEmber, 500);
        
        // Helper function to show error
        function showError(elementId, message) {
            const errorElement = document.getElementById(elementId);
            const inputElement = document.querySelector(`[name="${elementId.replace('Error', '')}"]`);
            
            if (errorElement) {
                errorElement.textContent = message;
                errorElement.style.display = 'block';
            }
            
            if (inputElement) {
                inputElement.parentElement.classList.add('has-error');
            }
            
            return false;
        }
        
        // Helper function to clear error
        function clearError(elementId) {
            const errorElement = document.getElementById(elementId);
            const inputElement = document.querySelector(`[name="${elementId.replace('Error', '')}"]`);
            
            if (errorElement) {
                errorElement.style.display = 'none';
            }
            
            if (inputElement) {
                inputElement.parentElement.classList.remove('has-error');
            }
        }
        
        // Clear all errors
        function clearAllErrors() {
            const errorElements = document.querySelectorAll('.error-message');
            errorElements.forEach(element => {
                element.style.display = 'none';
            });
            
            const formGroups = document.querySelectorAll('.form-group');
            formGroups.forEach(group => {
                group.classList.remove('has-error');
            });
            
            document.getElementById('formError').classList.add('hidden');
        }
        
        function validateStep1() {
            clearAllErrors();
            
            let isValid = true;
            const name = document.querySelector('input[name="name"]').value.trim();
            const email = document.querySelector('input[name="email"]').value.trim();
            const phone = document.querySelector('input[name="phone"]').value.trim();

            if (!name) {
                isValid = showError('nameError', 'Please enter your full name');
            }

            // Basic email validation
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email || !emailPattern.test(email)) {
                isValid = showError('emailError', 'Please enter a valid email address');
            }

            // Basic phone validation (at least 10 digits)
            const phonePattern = /^\d{10,}$/;
            if (!phone || !phonePattern.test(phone.replace(/[^0-9]/g, ''))) {
                isValid = showError('phoneError', 'Please enter a valid phone number (at least 10 digits)');
            }

            return isValid;
        }

        function nextStep() {
            if (!validateStep1()) {
                return;
            }

            const step1 = document.getElementById('step1');
            const step2 = document.getElementById('step2');

            step1.classList.add('slide-exit');

            // Remove the slide-enter class from step2 if it exists
            step2.classList.remove('slide-enter');

            setTimeout(() => {
                step1.classList.add('hidden');
                step1.classList.remove('slide-exit');

                step2.classList.remove('hidden');
                // Force a reflow
                void step2.offsetWidth;
                step2.classList.add('slide-enter');
            }, 300);
        }

        function previousStep() {
            const step1 = document.getElementById('step1');
            const step2 = document.getElementById('step2');

            step2.classList.add('slide-exit');
            step2.classList.remove('slide-enter');

            setTimeout(() => {
                step2.classList.add('hidden');
                step2.classList.remove('slide-exit');

                step1.classList.remove('hidden');
                // Force a reflow
                void step1.offsetWidth;
                step1.classList.add('slide-enter');
            }, 300);
        }

        function validateStep2() {
            clearAllErrors();
            
            let isValid = true;
            const college = document.querySelector('input[name="college_id"]').value.trim();
            const department = document.querySelector('input[name="department"]').value.trim();
            const password = document.querySelector('input[name="password"]').value;
            const confirmPassword = document.querySelector('input[name="confirm_password"]').value;

            if (!college) {
                isValid = showError('collegeError', 'Please enter your college');
            }
            
            if (!department) {
                isValid = showError('departmentError', 'Please enter your department');
            }
            
            if (!password || password.length < 6) {
                isValid = showError('passwordError', 'Password must be at least 6 characters');
            }
            
            if (password !== confirmPassword) {
                isValid = showError('confirmPasswordError', 'Passwords do not match');
            }
            
            return isValid;
        }

        document.getElementById('registrationForm').addEventListener('submit', function (e) {
            e.preventDefault();

            if (!validateStep2()) {
                return;
            }

            const formData = new FormData(this);
            const formError = document.getElementById('formError');
            const successMessage = document.getElementById('formSuccessMessage');

            fetch('registration_process.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        successMessage.style.display = 'block';
                        
                        // Hide form error if visible
                        formError.classList.add('hidden');
                        
                        // Auto-login after successful registration
                        const loginData = new FormData();
                        loginData.append('email_phone', formData.get('phone'));
                        loginData.append('password', formData.get('password'));

                        return fetch('login_process.php', {
                            method: 'POST', 
                            body: loginData
                        })
                        .then(response => response.json())
                        .then(loginData => {
                            if (!loginData.success) {
                                throw new Error(loginData.message || 'Auto-login failed');
                            }
                            
                            // Redirect after a short delay to show the success message
                            setTimeout(() => {
                                window.location.href = '../index.php';
                            }, 1500);
                        });
                    } else {
                        // Show form error with message
                        formError.textContent = data.message || 'Registration failed. Please try again.';
                        formError.classList.remove('hidden');
                        
                        // Hide success message if visible
                        successMessage.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    formError.textContent = 'An error occurred. Please try again.';
                    formError.classList.remove('hidden');
                    
                    // Hide success message if visible
                    successMessage.style.display = 'none';
                });
        });
    </script>
</body>

</html>