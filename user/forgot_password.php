<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
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

            0%,
            100% {
                transform: scale(1) rotate(0deg);
                opacity: 0.6;
            }

            50% {
                transform: scale(1.1) rotate(5deg);
                opacity: 0.8;
            }
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
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
            }

            100% {
                transform: translateY(-100px) rotate(360deg);
                opacity: 0;
            }
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
    </style>
</head>

<body class="bg-black pb-16 md:pb-0">
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

        <div class="max-w-7xl mx-auto py-12 flex flex-col items-center justify-center min-h-screen">
            <!-- Password Recovery Card -->
            <div class="w-full max-w-md relative z-10">
                <div class="bg-white p-8 rounded-2xl shadow-2xl">
                    <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Password Recovery</h1>

                    <!-- Error message container -->
                    <div id="error-message" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"></div>

                    <form id="passwordRecoveryForm" class="space-y-6">
                        <!-- Step 1: Email Input -->
                        <div id="step1" class="space-y-6">
                            <p class="text-gray-600 text-center">Enter your email address to receive a password reset
                                code.</p>

                            <div>
                                <input type="email" name="email" placeholder="Email Address" required
                                    class="w-full px-5 py-4 bg-gray-50 rounded-xl border-2 border-orange-200 text-gray-900 placeholder-gray-500 focus:border-orange-500 focus:ring-orange-500 transition-colors duration-300">
                            </div>

                            <button type="button" id="sendCodeBtn" onclick="sendResetCode()"
                                class="w-full py-4 px-6 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white rounded-xl font-medium transform transition-all duration-300 hover:-translate-y-1 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                Send Reset Code
                            </button>
                        </div>

                        <!-- Step 2: Code Verification -->
                        <div id="step2" class="space-y-6 hidden">
                            <p class="text-gray-600 text-center">Enter the 6-digit code sent to your email.</p>

                            <div>
                                <input type="text" name="verification_code" placeholder="Verification Code" required
                                    maxlength="6"
                                    class="w-full px-5 py-4 bg-gray-50 rounded-xl border-2 border-orange-200 text-gray-900 placeholder-gray-500 focus:border-orange-500 focus:ring-orange-500 transition-colors duration-300">
                            </div>

                            <div class="flex gap-4">
                                <button type="button" onclick="previousStep(1)"
                                    class="w-1/3 py-4 px-6 text-orange-600 bg-orange-50 hover:bg-orange-100 rounded-xl font-medium transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                    Back
                                </button>

                                <button type="button" id="verifyButton" onclick="verifyCode()"
                                    class="w-2/3 py-4 px-6 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white rounded-xl font-medium transform transition-all duration-300 hover:-translate-y-1 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                    Verify Code
                                </button>
                            </div>

                            <div class="text-center">
                                <button type="button" id="resendButton" onclick="resendCode()"
                                    class="text-orange-600 hover:text-orange-700 font-medium text-sm">
                                    Resend code
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Password Reset -->
                        <div id="step3" class="space-y-6 hidden">
                            <p class="text-gray-600 text-center">Create a new password for your account.</p>

                            <div>
                                <input type="password" name="new_password" placeholder="New Password" required
                                    class="w-full px-5 py-4 bg-gray-50 rounded-xl border-2 border-orange-200 text-gray-900 placeholder-gray-500 focus:border-orange-500 focus:ring-orange-500 transition-colors duration-300">
                            </div>

                            <div>
                                <input type="password" name="confirm_password" placeholder="Confirm New Password"
                                    required
                                    class="w-full px-5 py-4 bg-gray-50 rounded-xl border-2 border-orange-200 text-gray-900 placeholder-gray-500 focus:border-orange-500 focus:ring-orange-500 transition-colors duration-300">
                            </div>

                            <div class="flex gap-4">
                                <button type="button" onclick="previousStep(2)"
                                    class="w-1/3 py-4 px-6 text-orange-600 bg-orange-50 hover:bg-orange-100 rounded-xl font-medium transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                    Back
                                </button>

                                <button type="button" id="resetButton" onclick="resetPassword()"
                                    class="w-2/3 py-4 px-6 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white rounded-xl font-medium transform transition-all duration-300 hover:-translate-y-1 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                    Reset Password
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-6 text-center">
                        <p class="text-gray-600">
                            Remember your password?
                            <a href="login.php"
                                class="text-orange-600 hover:text-orange-700 font-medium transition-colors duration-300">
                                Log in now
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Create animated embers effect
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

        // Helper function to show error messages
        function showError(message) {
            const errorEl = document.getElementById('error-message');
            errorEl.textContent = message;
            errorEl.classList.remove('hidden');
        }

        // Helper function to hide error messages
        function hideError() {
            const errorEl = document.getElementById('error-message');
            errorEl.classList.add('hidden');
        }

        // Email validation function
        function validateEmail(email) {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailPattern.test(email);
        }

        // Navigate between steps with animation
        function navigateToStep(fromStep, toStep) {
            const fromEl = document.getElementById(`step${fromStep}`);
            const toEl = document.getElementById(`step${toStep}`);

            // Hide any existing errors
            hideError();

            fromEl.classList.add('slide-exit');
            
            setTimeout(() => {
                fromEl.classList.add('hidden');
                fromEl.classList.remove('slide-exit');

                toEl.classList.remove('hidden');
                // Force a reflow
                void toEl.offsetWidth;
                toEl.classList.add('slide-enter');
            }, 300);
        }

        function previousStep(step) {
            navigateToStep(step + 1, step);
        }

        // Step 1: Send reset code to email
        function sendResetCode() {
            // Hide any previous error
            hideError();
            
            const email = document.querySelector('input[name="email"]').value.trim();

            if (!email) {
                showError('Please enter your email address');
                return;
            }

            if (!validateEmail(email)) {
                showError('Please enter a valid email address');
                return;
            }

            // Create a loading indicator
            const submitButton = document.getElementById('sendCodeBtn');
            const originalText = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.textContent = 'Sending...';

            // Prepare form data
            const formData = new FormData();
            formData.append('email', email);

            // Send AJAX request to the server
            fetch('send_reset_code.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Store email in session storage for resend functionality
                        sessionStorage.setItem('resetEmail', email);

                        // Navigate to step 2
                        navigateToStep(1, 2);
                    } else {
                        // Show error message
                        showError(data.message || 'An error occurred. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('An error occurred. Please try again later.');
                })
                .finally(() => {
                    // Restore button state
                    submitButton.disabled = false;
                    submitButton.textContent = originalText;
                });
        }

        // Resend verification code
        function resendCode() {
            // Hide any previous error
            hideError();
            
            // Get email from session storage
            const email = sessionStorage.getItem('resetEmail');

            if (!email) {
                showError('Email not found. Please go back and enter your email again.');
                return;
            }

            // Create a loading indicator
            const resendButton = document.getElementById('resendButton');
            const originalText = resendButton.textContent;
            resendButton.disabled = true;
            resendButton.textContent = 'Sending...';

            // Prepare form data
            const formData = new FormData();
            formData.append('email', email);

            // Send AJAX request to the server
            fetch('send_reset_code.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        alert('A new verification code has been sent to your email');
                    } else {
                        // Show error message
                        showError(data.message || 'An error occurred. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('An error occurred. Please try again later.');
                })
                .finally(() => {
                    // Restore button state
                    resendButton.disabled = false;
                    resendButton.textContent = originalText;
                });
        }

        // Step 2: Verify the code
        function verifyCode() {
            // Hide any previous error
            hideError();

            // Get and validate the code
            const code = document.querySelector('input[name="verification_code"]').value.trim();

            if (!code) {
                showError('Please enter the verification code');
                return;
            }

            if (code.length !== 6 || !/^\d+$/.test(code)) {
                showError('Please enter a valid 6-digit code');
                return;
            }

            // Show loading indicator
            const submitButton = document.getElementById('verifyButton');
            submitButton.disabled = true;
            submitButton.textContent = 'Verifying...';

            // AJAX request to verify the code
            fetch('verify_code.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ code: code })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Code verified successfully, show step 3
                        navigateToStep(2, 3);
                    } else {
                        // Code verification failed
                        showError(data.message || 'Invalid verification code. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('An error occurred while verifying your code. Please try again.');
                })
                .finally(() => {
                    // Reset button state
                    submitButton.disabled = false;
                    submitButton.textContent = 'Verify Code';
                });
        }

        // Step 3: Reset password
        function resetPassword() {
            // Hide any previous error
            hideError();
            
            const newPassword = document.querySelector('input[name="new_password"]').value;
            const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
            const email = sessionStorage.getItem('resetEmail');
            const code = document.querySelector('input[name="verification_code"]').value.trim();

            if (!newPassword || !confirmPassword) {
                showError('Please fill in all password fields');
                return;
            }

            if (newPassword !== confirmPassword) {
                showError('Passwords do not match');
                return;
            }

            if (newPassword.length < 8) {
                showError('Password should be at least 8 characters long');
                return;
            }

            // Show loading indicator
            const resetButton = document.getElementById('resetButton');
            resetButton.disabled = true;
            resetButton.textContent = 'Resetting...';

            // Prepare data for the API call
            const formData = new FormData();
            formData.append('email', email);
            formData.append('code', code);
            formData.append('new_password', newPassword);

            // Send AJAX request to reset the password
            fetch('reset_password.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Password reset successful
                        alert('Password reset successful! You can now log in with your new password.');
                        window.location.href = 'login.php';
                    } else {
                        // Show error message
                        showError(data.message || 'Failed to reset password. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('An error occurred. Please try again later.');
                })
                .finally(() => {
                    // Reset button state
                    resetButton.disabled = false;
                    resetButton.textContent = 'Reset Password';
                });
        }
    </script>
</body>

</html>