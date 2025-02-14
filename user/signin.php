<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../header/links.php'; ?>
    <?php include '../header/navbar_styles.php'; ?>
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
            transform: translateY(20px);
            opacity: 0;
            animation: slideUp 0.6s ease forwards;
        }

        @keyframes slideUp {
            to {
                transform: translateY(0);
                opacity: 1;
            }
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
            transition: color 0.3s ease;
        }

        .input-field:focus + .input-icon {
            color: #4f46e5;
        }

        .floating-shapes div {
            position: absolute;
            pointer-events: none;
            animation: float 20s infinite;
            opacity: 0.1;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            25% { transform: translate(100px, -100px) rotate(90deg); }
            50% { transform: translate(0, -200px) rotate(180deg); }
            75% { transform: translate(-100px, -100px) rotate(270deg); }
        }
    </style>
</head>

<body class="bg-gray-50">
    <?php include '../header/navbar.php'; ?>

    <div class="min-h-screen pt-20 px-4 sm:px-6 lg:px-8 gradient-background bg-[length:400%_400%] relative overflow-hidden">
        <!-- Floating background shapes -->
        <div class="floating-shapes">
            <div class="w-64 h-64 bg-white rounded-full left-[10%] top-[20%]"></div>
            <div class="w-48 h-48 bg-white rounded-lg right-[15%] top-[30%]"></div>
            <div class="w-32 h-32 bg-white rotate-45 left-[20%] bottom-[20%]"></div>
        </div>

        <div class="max-w-md mx-auto py-12">
            <div class="form-container p-8 rounded-3xl">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800">Welcome!</h2>
                    <p class="text-gray-600 mt-2">Sign in to your account</p>
                </div>

                <!-- Error/Success Messages -->
                <div id="messageBox" class="hidden mb-6 transform transition-all duration-300"></div>

                <form id="loginForm" action="process_login.php" method="POST" class="space-y-6">
                    <div class="space-y-5">
                        <div>
                            <div class="relative">
                                <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                                <input type="text" id="email_phone" name="email_phone" required
                                    placeholder="Email or Phone Number"
                                    class="input-field w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-0">
                            </div>
                        </div>

                        <div>
                            <div class="relative">
                                <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <input type="password" id="password" name="password" required
                                    placeholder="Password"
                                    class="input-field w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-0">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>
                        <a href="forgot_password.php" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                            Forgot password?
                        </a>
                    </div>

                    <button type="submit"
                        class="w-full py-3 px-6 text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 rounded-xl font-medium transform transition-all duration-300 hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg hover:shadow-xl">
                        Sign In
                    </button>

                    <p class="text-center text-sm text-gray-600 mt-4">
                        Don't Registered Yet?
                        <a href="signup.php" class="font-medium text-indigo-600 hover:text-indigo-500">
                            Sign up now
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);

            fetch('process_signin.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const messageBox = document.getElementById('messageBox');
                messageBox.classList.remove('hidden');
                
                if (data.success) {
                    messageBox.className = 'mb-6 p-4 rounded-xl bg-green-100 text-green-700 border border-green-200 flex items-center';
                    messageBox.innerHTML = `
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        ${data.message}
                    `;
                    // Redirect after successful login
                    setTimeout(() => {
                        window.location.href = '../index.php';
                    }, 1000);
                } else {
                    messageBox.className = 'mb-6 p-4 rounded-xl bg-red-100 text-red-700 border border-red-200 flex items-center';
                    messageBox.innerHTML = `
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        ${data.message}
                    `;
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
    </script>

    <?php include '../header/navbar_scripts.php'; ?>
</body>
</html>