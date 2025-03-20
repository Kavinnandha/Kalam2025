<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration Dashboard</title>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/jquery.dataTables.min.css">
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
    <!-- DataTables Buttons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.2.3/js/buttons.print.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.2.3/css/buttons.dataTables.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-indigo-600 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <span class="text-white text-xl font-bold">Event Registration Dashboard</span>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <button class="bg-indigo-800 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Admin Panel
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="px-4 py-6 sm:px-0">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold text-gray-900">Event Registrations</h1>
                    <div class="flex space-x-3">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input id="global-search" type="text" class="pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Global Search">
                        </div>
                        <div class="inline-flex rounded-md shadow-sm">
                            <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Refresh Data
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Dashboard Stats -->
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                    <!-- Total Registrations -->
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Registrations</dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-gray-900" id="total-registrations">Loading...</div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Revenue -->
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-gray-900" id="total-revenue">Loading...</div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Events -->
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Events</dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-gray-900" id="total-events">Loading...</div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Transactions -->
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Recent Transactions</dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-gray-900" id="recent-transactions">Loading...</div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Registration Details</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Comprehensive list of all event registrations.</p>
                    </div>
                    <div class="px-4 py-3 sm:px-6">
                        <table id="registrations-table" class="min-w-full divide-y divide-gray-200 stripe hover" style="width:100%">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">College ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="table-body">
                                <!-- Data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500">
                    &copy; 2025 Event Registration System. All rights reserved.
                </p>
            </div>
        </footer>
    </div>

    <script>
        // Connect to database and fetch data
        $(document).ready(function() {
            // Initialize DataTable with buttons for export
            var table = $('#registrations-table').DataTable({
                processing: true,
                serverSide: false, // We're loading all data at once for this example
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                // Define columns explicitly to match JSON property names
                columns: [
                    { data: 'name' },
                    { data: 'email' },
                    { data: 'phone' },
                    { data: 'college_id' },
                    { data: 'department' },
                    { data: 'event_name' },
                    { data: 'category' },
                    { data: 'order_date' },
                    { data: 'transaction_id' },
                    { data: 'amount' }
                ]
            });

            // Fetch data from PHP backend
            $.ajax({
                url: 'summary_fetch.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Clear existing data
                    table.clear();
                    
                    // Add new data and redraw
                    table.rows.add(data).draw();
                    
                    // Update dashboard stats
                    updateDashboardStats(data);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                    alert('Failed to load data. Please try again later.');
                }
            });

            // Set up global search functionality
            $('#global-search').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Function to update dashboard stats
            function updateDashboardStats(data) {
                // Total registrations
                $('#total-registrations').text(data.length);
                
                // Total revenue
                var totalRevenue = data.reduce(function(sum, registration) {
                    return sum + parseFloat(registration.amount || 0);
                }, 0);
                $('#total-revenue').text('₹' + totalRevenue.toFixed(2));
                
                // Count unique events
                const uniqueEvents = new Set(data.map(item => item.event_name));
                $('#total-events').text(uniqueEvents.size);
                
                // Count transactions from the last 24 hours
                const yesterday = new Date();
                yesterday.setDate(yesterday.getDate() - 1);
                
                const recentTransactions = data.filter(item => {
                    const orderDate = new Date(item.order_date);
                    return orderDate >= yesterday;
                }).length;
                
                $('#recent-transactions').text(recentTransactions);
            }
        });
    </script>
</body>
</html>