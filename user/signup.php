<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../header/links.php'; ?>
    <?php include '../header/navbar_styles.php'; ?>
    <style>
        .gradient-background {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .form-container {
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            background-color: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(209, 213, 219, 0.3);
        }
        .input-field {
            transition: all 0.3s ease;
        }
        .input-field:focus {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-gray-50">
    <?php include '../header/navbar.php'; ?>

    <div class="min-h-screen pt-20 px-4 sm:px-6 lg:px-8 gradient-background">
        <div class="max-w-4xl mx-auto py-12 flex flex-col lg:flex-row items-center gap-8">
            <!-- Left side content -->
            <div class="lg:w-1/2 text-white space-y-6 text-center lg:text-left">
                <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl">
                    Join Our Events
                </h1>
                <p class="text-xl text-gray-100">
                    Register and be part of something extraordinary.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Easy Registration</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span>Secure Process</span>
                    </div>
                </div>
            </div>

            <!-- Right side form -->
            <div class="lg:w-1/2 w-full">
                <div class="form-container p-8 rounded-2xl shadow-2xl">
                    <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Register Now</h2>

                    <!-- Error/Success Messages -->
                    <div id="messageBox" class="hidden mb-4 p-4 rounded-lg transform transition-all duration-300"></div>

                    <form id="signupForm" action="process_signup.php" method="POST" class="space-y-6">
                        <div class="space-y-5">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <input type="text" id="name" name="name" required
                                    class="input-field w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <input type="email" id="email" name="email" required
                                    class="input-field w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input type="tel" id="phone" name="phone" required
                                    class="input-field w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <div>
                                <label for="college_id" class="block text-sm font-medium text-gray-700 mb-1">College</label>
                                <input type="text" id="college_id" name="college_id" required
                                    class="input-field w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <div>
                                <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                                <input type="text" id="department" name="department" required
                                    class="input-field w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full py-3 px-6 text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-lg font-medium transform transition-all duration-300 hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg hover:shadow-xl">
                            Register
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('signupForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('process_signup.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    const messageBox = document.getElementById('messageBox');
                    messageBox.classList.remove('hidden');
                    messageBox.classList.add('transform', 'translate-y-0', 'opacity-100');

                    if (data.success) {
                        messageBox.className = 'mb-4 p-4 rounded-lg bg-green-100 text-green-700 border border-green-200 flex items-center';
                        messageBox.innerHTML = `
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            ${data.message}
                        `;
                        document.getElementById('signupForm').reset();
                    } else {
                        messageBox.className = 'mb-4 p-4 rounded-lg bg-red-100 text-red-700 border border-red-200 flex items-center';
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
                    const messageBox = document.getElementById('messageBox');
                    messageBox.classList.remove('hidden');
                    messageBox.className = 'mb-4 p-4 rounded-lg bg-red-100 text-red-700 border border-red-200 flex items-center';
                    messageBox.innerHTML = `
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        An error occurred. Please try again.
                    `;
                });
        });
    </script>

    <?php include '../header/navbar_scripts.php'; ?>
</body>

</html>