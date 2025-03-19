<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$superAdmin = $_SESSION['is_superadmin'];
?>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: {
                        50: '#fff7ed',
                        100: '#ffedd5',
                        200: '#fed7aa',
                        300: '#fdba74',
                        400: '#fb923c',
                        500: '#f97316',
                        600: '#ea580c',
                        700: '#c2410c',
                        800: '#9a3412',
                        900: '#7c2d12',
                    }
                }
            }
        }
    }
</script>
<nav class="bg-gradient-to-r from-primary-600 to-primary-700 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <h1 class="text-2xl font-bold text-white">Event Management</h1>
            </div>
            <div class="flex items-center space-x-3">
                <a href="<?php echo ($superAdmin == 'yes' ? "manage_events_admin.php" : "manage_events.php"); ?>"
                    class="bg-white text-primary-600 px-3 py-2 rounded-md text-sm font-medium hover:bg-primary-50 transition duration-300 shadow-sm">
                    <i class="fas fa-users mr-1"></i>Events
                </a>
                <a href="sponsors.php"
                    class="bg-white text-primary-600 px-3 py-2 rounded-md text-sm font-medium hover:bg-primary-50 transition duration-300 shadow-sm">
                    <i class="fas fa-users mr-1"></i>Sponsors
                </a>
                <a href="view_team.php"
                    class="bg-white text-primary-600 px-3 py-2 rounded-md text-sm font-medium hover:bg-primary-50 transition duration-300 shadow-sm">
                    <i class="fas fa-users mr-1"></i>Team
                </a>
                <a href="view_cart.php"
                    class="bg-white text-primary-600 px-3 py-2 rounded-md text-sm font-medium hover:bg-primary-50 transition duration-300 shadow-sm">
                    <i class="fas fa-shopping-cart mr-1"></i>Cart
                </a>
                <a href="add_event.php"
                    class="bg-white text-primary-600 px-3 py-2 rounded-md text-sm font-medium hover:bg-primary-50 transition duration-300 shadow-sm">
                    <i class="fas fa-plus mr-1"></i>New Event
                </a>
                <a href="view_hackathon_teams.php"
                    class="bg-white text-primary-600 px-3 py-2 rounded-md text-sm font-medium hover:bg-primary-50 transition duration-300 shadow-sm">
                    <i class="fas fa-laptop-code mr-1"></i>Hackathon
                </a>
                <?php if ($superAdmin == "yes"): ?>
                    <a href="registered_users.php"
                        class="bg-white text-primary-600 px-3 py-2 rounded-md text-sm font-medium hover:bg-primary-50 transition duration-300 shadow-sm">
                        <i class="fas fa-clipboard-list mr-1"></i>Users
                    </a>
                    <a href="create_user.php"
                        class="bg-white text-primary-600 px-3 py-2 rounded-md text-sm font-medium hover:bg-primary-50 transition duration-300 shadow-sm">
                        <i class="fas fa-users-cog mr-1"></i>Manage
                    </a>
                <?php endif; ?>
                <a href="logout.php" class="text-white hover:text-primary-100 transition duration-300">
                    <i class="fas fa-sign-out-alt mr-1"></i>Logout
                </a>
            </div>
        </div>
    </div>
</nav>