<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../header/links.php'; ?>
    <?php include '../header/navbar_styles.php'; ?>
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
    </style>
</head>

<body class="bg-black pb-16">
    <?php include '../header/navbar.php'; ?>

    <div class="min-h-screen pt-20 px-4 sm:px-6 lg:px-8 fire-gradient relative overflow-hidden">
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

        <div class="max-w-md mx-auto py-12 relative">
            <div
                class="bg-white rounded-2xl shadow-2xl p-8 transform transition-transform duration-500 hover:scale-[1.02]">
                <div class="text-center mb-8">
                    <h2 class="text-4xl font-bold text-gray-900 mb-2">KALAM 2025</h2>
                    <p class="text-orange-600">Log in to your account</p>
                </div>

                <div id="messageBox" class="hidden mb-6 transform transition-all duration-300"></div>

                <form id="loginForm" action="login_process.php" method="POST" class="space-y-6">
                    <div class="space-y-5">
                        <div class="relative">
                            <input type="text" id="email_phone" name="email_phone" required
                                placeholder="Email or Phone Number"
                                class="w-full px-5 py-4 bg-gray-50 rounded-xl border-2 border-orange-200 text-gray-900 placeholder-gray-500 focus:border-orange-500 focus:ring-orange-500 transition-colors duration-300">
                        </div>

                        <div class="relative">
                            <input type="password" id="password" name="password" required placeholder="Password"
                                class="w-full px-5 py-4 bg-gray-50 rounded-xl border-2 border-orange-200 text-gray-900 placeholder-gray-500 focus:border-orange-500 focus:ring-orange-500 transition-colors duration-300">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox"
                                class="w-4 h-4 rounded border-orange-300 text-orange-500 focus:ring-orange-500">
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>
                        <a href="forgot_password.php"
                            class="text-sm font-medium text-orange-600 hover:text-orange-500 transition-colors">
                            Forgot password?
                        </a>
                    </div>

                    <button type="submit"
                        class="w-full py-4 px-6 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white rounded-xl font-medium transform transition-all duration-300 hover:-translate-y-1 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        Log In
                    </button>

                    <p class="text-center text-sm text-gray-600 mt-4">
                        Don't registered yet?
                        <a href="registration.php"
                            class="font-medium text-orange-600 hover:text-orange-500 transition-colors">
                            Register now
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>


    <script>
        // Add dynamic ember creation
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

        document.getElementById('loginForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('login_process.php', {
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

</body>

</html>