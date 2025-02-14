<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration</title>
    <?php include '../header/links.php'; ?>
    <?php include '../header/navbar_styles.php'; ?>
    <!-- Add Heroicons (You can also use other icon libraries like FontAwesome) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/heroicons/1.0.1/solid/style.min.css">
    <style>
        .gradient-background {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #2563eb 100%);
            animation: gradientShift 15s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .form-container {
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            background-color: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(209, 213, 219, 0.3);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .input-field {
            transition: all 0.3s ease;
            padding-left: 2.5rem !important;
        }

        .input-field:focus {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.1);
        }

        .input-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            width: 1.25rem;
            height: 1.25rem;
        }

        .success-animation {
            animation: successPulse 0.5s ease-out;
        }

        @keyframes successPulse {
            0% { transform: scale(0.95); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }

        .form-label {
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
            display: block;
        }

        .sign-in-link {
            transition: all 0.3s ease;
        }

        .sign-in-link:hover {
            color: #4f46e5;
            text-decoration: underline;
        }
    </style>
</head>

<body class="bg-gray-50">
    <?php include '../header/navbar.php'; ?>

    <div class="min-h-screen pt-16 px-4 sm:px-6 lg:px-8 gradient-background bg-[length:400%_400%]">
        <div class="max-w-7xl mx-auto py-12 flex flex-col lg:flex-row items-center justify-between gap-12">
            <!-- Left side content -->
            <div class="lg:w-5/12 text-white space-y-8 text-center lg:text-left">
                <h1 class="text-5xl font-extrabold tracking-tight sm:text-6xl bg-clip-text text-transparent bg-gradient-to-r from-white to-indigo-200">
                    Join Our Events
                </h1>
                <p class="text-xl text-gray-100 leading-relaxed">
                    Be part of our vibrant community and experience extraordinary events designed for students like you.
                </p>
                <div class="flex flex-col sm:flex-row gap-6 justify-center lg:justify-start">
                    <div class="flex items-center gap-3 bg-white/10 rounded-lg px-4 py-3 backdrop-blur-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Quick Registration</span>
                    </div>
                    <div class="flex items-center gap-3 bg-white/10 rounded-lg px-4 py-3 backdrop-blur-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span>Secure Process</span>
                    </div>
                </div>
            </div>

            <!-- Right side form -->
            <div class="lg:w-6/12 w-full">
                <div class="form-container p-8 rounded-3xl">
                    <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Register</h2>

                    <!-- Error/Success Messages -->
                    <div id="messageBox" class="hidden mb-6 transform transition-all duration-300"></div>

                    <form id="signupForm" action="process_signup.php" method="POST" class="space-y-6">
                        <div class="space-y-5">
                            <div>
                                <label for="name" class="form-label">Full Name</label>
                                <div class="relative">
                                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <input type="text" id="name" name="name" required
                                        class="input-field w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-0">
                                </div>
                            </div>

                            <div>
                                <label for="email" class="form-label">Email Address</label>
                                <div class="relative">
                                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <input type="email" id="email" name="email" required
                                        class="input-field w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-0">
                                </div>
                            </div>

                            <div>
                                <label for="phone" class="form-label">Phone Number</label>
                                <div class="relative">
                                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <input type="tel" id="phone" name="phone" required
                                        class="input-field w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-0">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label for="college_id" class="form-label">College</label>
                                    <div class="relative">
                                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <input type="text" id="college_id" name="college_id" required
                                            class="input-field w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-0">
                                    </div>
                                </div>

                                <div>
                                    <label for="department" class="form-label">Department</label>
                                    <div class="relative">
                                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <input type="text" id="department" name="department" required
                                            class="input-field w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-0">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="password" class="form-label">Password</label>
                                <div class="relative">
                                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    <input type="password" id="password" name="password" required
                                        class="input-field w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-0">
                                </div>
                            </div>

                            <div>
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <div class="relative">
                                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    <input type="password" id="confirm_password" name="confirm_password" required
                                        class="input-field w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-0">
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full py-4 px-6 text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 rounded-xl font-medium transform transition-all duration-300 hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg hover:shadow-xl">
                            Register
                        </button>

                         <!-- Add sign in link -->
                         <div class="text-center">
                            <p class="text-gray-600">
                                Already registered? 
                                <a href="signin.php" class="sign-in-link font-medium text-indigo-600 hover:text-indigo-700">
                                    Sign in now
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Password validation
            let password = document.getElementById("password").value;
            let confirmPassword = document.getElementById("confirm_password").value;
            
            if (password !== confirmPassword) {
                showMessage({
                    success: false,
                    message: "Passwords do not match!"
                });
                return;
            }

            // Basic password strength check
            if (password.length < 8) {
                showMessage({
                    success: false,
                    message: "Password must be at least 8 characters long!"
                });
                return;
            }

            const formData = new FormData(this);

            fetch('process_signup.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                showMessage(data);
                if (data.success) {
                    document.getElementById('signupForm').reset();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage({
                    success: false,
                    message: "An error occurred. Please try again."
                });
            });
        });

        function showMessage(data) {
            const messageBox = document.getElementById('messageBox');
            messageBox.classList.remove('hidden');
            
            if (data.success) {
                messageBox.className = 'mb-6 p-4 rounded-xl bg-green-100 text-green-700 border border-green-200 flex items-center success-animation';
                messageBox.innerHTML = `
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    ${data.message}
                `;
            } else {
                messageBox.className = 'mb-6 p-4 rounded-xl bg-red-100 text-red-700 border border-red-200 flex items-center';
                messageBox.innerHTML = `
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    ${data.message}
                `;
            }

            // Auto-hide message after 5 seconds
            setTimeout(() => {
                messageBox.classList.add('hidden');
            }, 9000);
        }
    </script>

    <?php include '../header/navbar_scripts.php'; ?>
</body>
</html>