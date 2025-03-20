<?php 
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration Dashboard</title>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- JSZip for Excel export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
    <!-- SheetJS for Excel export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
    <style>
        /* Excel button */
        .excel-button {
            background-color: #1d6f42 !important;
            color: white !important;
            border: none !important;
            padding: 6px 12px !important;
            border-radius: 4px !important;
            cursor: pointer !important;
            margin-right: 8px !important;
        }

        .excel-button:hover {
            background-color: #155d38 !important;
        }

        /* Refresh button */
        .refresh-button {
            background-color: #4a5568 !important;
            color: white !important;
            border: none !important;
            padding: 6px 12px !important;
            border-radius: 4px !important;
            cursor: pointer !important;
        }

        .refresh-button:hover {
            background-color: #2d3748 !important;
        }

        /* Visit button */
        .visit-button {
            background-color: #2563eb !important;
            color: white !important;
            border: none !important;
            padding: 4px 8px !important;
            border-radius: 4px !important;
            cursor: pointer !important;
            font-size: 0.875rem !important;
            width: 100%;
        }

        .visit-button:hover {
            background-color: #1d4ed8 !important;
        }

        /* Visit confirmed style */
        .visit-confirmed {
            background-color: #10b981 !important;
        }

        .visit-confirmed:hover {
            background-color: #059669 !important;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <?php include 'navigation.php'; ?>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="px-4 py-6 sm:px-0">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                    <h1 class="text-2xl font-semibold text-gray-900 mb-4 sm:mb-0">Registration Details</h1>
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                        <div class="relative w-full sm:w-64">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input id="global-search" type="text"
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Search by phone, id, name, college ID...">
                        </div>
                        <button id="export-excel" class="excel-button w-full sm:w-auto">
                            <svg class="inline-block h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Export to Excel
                        </button>
                        <button id="refresh-data" class="refresh-button w-full sm:w-auto">
                            <svg class="inline-block h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Refresh Data
                        </button>
                    </div>
                </div>

                <!-- Cards Container -->
                <div id="cards-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Registration cards will be loaded here -->
                </div>

                <!-- No Results Message -->
                <div id="no-results" class="hidden mt-4 p-6 bg-white shadow sm:rounded-lg">
                    <p class="text-gray-500 text-center">No registrations found matching your search criteria.</p>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white mt-8">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500">
                    &copy; 2025 Event Registration System. All rights reserved.
                </p>
            </div>
        </footer>
    </div>

    <!-- Card Template (Hidden) -->
    <template id="user-card-template">
        <div class="user-card bg-white shadow overflow-hidden sm:rounded-lg mb-4">
            <div class="px-4 py-5 sm:px-6 bg-gray-50">
                <div class="flex justify-between">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 user-name">User Name</h3>
                    <span class="text-sm text-gray-500">(User Id: <span class="user-id">12345</span>)</span>
                    <div class="visit-status-container">
                        <!-- Visit button will be inserted here -->
                    </div>
                </div>
                <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap">
                    <div class="mt-1 flex items-center text-sm text-gray-500 mr-6">
                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                        </svg>
                        <span class="user-phone">Phone Number</span>
                    </div>
                    <div class="mt-1 flex items-center text-sm text-gray-500">
                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="user-college-id">College ID</span>
                    </div>
                </div>
                <div class="mt-2">
                    <span
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 total-amount">
                        Total: ₹0.00
                    </span>
                </div>
            </div>
            <div class="border-t border-gray-200">
                <div class="registration-events px-4 py-5 sm:p-0">
                    <dl class="sm:divide-y sm:divide-gray-200">
                        <!-- Event registrations will be inserted here -->
                    </dl>
                </div>
            </div>
        </div>
    </template>

    <!-- Event Row Template (Hidden) -->
    <template id="event-row-template">
        <div class="event-row py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500 event-name">Event Name</dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-1 event-category">Category</dd>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-1 event-amount">Amount</dd>
        </div>
    </template>

    <script>
        $(document).ready(function () {
            // Global variables
            let allRegistrations = [];

            // Function to load data
            function loadData() {
                // Show loading state
                $('#cards-container').html('<div class="col-span-full text-center py-12"><svg class="animate-spin h-8 w-8 mx-auto text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><p class="mt-2 text-gray-500">Loading registrations...</p></div>');

                // Fetch data from PHP backend
                $.ajax({
                    url: 'summary_fetch.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        // Store all registrations
                        allRegistrations = data;

                        // Process and display data
                        processAndDisplayData(allRegistrations);
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching data:', error);
                        $('#cards-container').html('<div class="col-span-full text-center py-12"><p class="text-red-500">Failed to load data. Please try again later.</p></div>');
                    }
                });
            }

            // Process data and display cards
            // Process data and display cards
            function processAndDisplayData(data, searchTerm = '') {
                // Group registrations by user_id
                const userMap = {};

                // Filter data if search term is provided
                let filteredData = data;
                if (searchTerm && searchTerm.trim() !== '') {
                    searchTerm = searchTerm.toLowerCase().trim();
                    filteredData = data.filter(reg =>
                        (reg.name && reg.name.toLowerCase().includes(searchTerm)) ||
                        (reg.phone && reg.phone.toLowerCase().includes(searchTerm)) ||
                        (reg.college_id && reg.college_id.toLowerCase().includes(searchTerm)) ||
                        (reg.event_name && reg.event_name.toLowerCase().includes(searchTerm)) ||
                        (reg.category && reg.category.toLowerCase().includes(searchTerm)) ||
                        (reg.user_id && reg.user_id.toString().includes(searchTerm))
                    );
                }

                // Group by user_id
                filteredData.forEach(registration => {
                    const userId = registration.user_id;
                    if (!userMap[userId]) {
                        userMap[userId] = {
                            user_id: userId,
                            name: registration.name,
                            phone: registration.phone,
                            college_id: registration.college_id,
                            visited: registration.visited,
                            total_amount: parseFloat(registration.total_amount || 0),
                            events: []
                        };
                    }

                    userMap[userId].events.push({
                        event_name: registration.event_name,
                        category: registration.category,
                        department_name: registration.department_name,
                        amount: parseFloat(registration.amount || 0)
                    });
                });

                // Convert to array
                const usersArray = Object.values(userMap);

                // Clear container
                $('#cards-container').empty();

                // Show no results message if needed
                if (usersArray.length === 0) {
                    $('#no-results').removeClass('hidden');
                } else {
                    $('#no-results').addClass('hidden');

                    // Create and append user cards
                    usersArray.forEach(user => {
                        createUserCard(user);
                    });

                    // Setup visit button handlers
                    setupVisitButtons();
                }
            }

            // Create user card
            // Create user card
            function createUserCard(user) {
                // Clone the template
                const template = document.getElementById('user-card-template');
                const card = document.importNode(template.content, true);

                // Set user data
                card.querySelector('.user-name').textContent = user.name;
                card.querySelector('.user-id').textContent = user.user_id;
                card.querySelector('.user-phone').textContent = user.phone;
                card.querySelector('.user-college-id').textContent = user.college_id || 'N/A';
                card.querySelector('.total-amount').textContent = `Total: ₹${user.total_amount.toFixed(2)}`;

                // Create visit button
                const visitButton = document.createElement('button');
                visitButton.className = user.visited === 'yes' ? 'visit-button visit-confirmed' : 'visit-button';
                visitButton.textContent = user.visited === 'yes' ? 'Visited' : 'Mark Visit';
                visitButton.setAttribute('data-user-id', user.user_id);
                visitButton.setAttribute('data-visited', user.visited);

                // Append visit button
                card.querySelector('.visit-status-container').appendChild(visitButton);

                // Add events
                const eventsContainer = card.querySelector('.registration-events dl');
                user.events.forEach(event => {
                    const eventTemplate = document.getElementById('event-row-template');
                    const eventRow = document.importNode(eventTemplate.content, true);

                    eventRow.querySelector('.event-name').textContent = event.event_name;
                    eventRow.querySelector('.event-category').textContent = event.category;
                    eventRow.querySelector('.event-amount').textContent = `₹${event.amount.toFixed(2)}`;

                    eventsContainer.appendChild(eventRow);
                });

                // Append to container
                document.getElementById('cards-container').appendChild(card);
            }

            // Set up visit button handlers
            function setupVisitButtons() {
                $('.visit-button').off('click').on('click', function () {
                    const userId = $(this).data('user-id');
                    const currentStatus = $(this).data('visited');
                    const newStatus = currentStatus === 'yes' ? 'no' : 'yes';

                    // Update visit status through AJAX
                    $.ajax({
                        url: 'summary_update.php',
                        type: 'POST',
                        data: {
                            user_id: userId,
                            visited: newStatus
                        },
                        success: function (response) {
                            if (response.success) {
                                // Reload data to reflect the changes
                                loadData();
                            } else {
                                alert('Failed to update visit status: ' + response.message);
                            }
                        },
                        error: function () {
                            alert('Failed to update visit status. Please try again later.');
                        }
                    });
                });
            }

            // Export to Excel function
            function exportToExcel() {
                // Create worksheet data
                const wsData = [];

                // Add headers
                wsData.push(['Name', 'Phone', 'College ID', 'Event', 'Category', 'Department', 'Amount', 'Visited']);

                // Add registration data
                allRegistrations.forEach(reg => {
                    wsData.push([
                        reg.name || '',
                        reg.phone || '',
                        reg.college_id || '',
                        reg.event_name || '',
                        reg.category || '',
                        reg.department_name || '',
                        parseFloat(reg.amount || 0),
                        reg.visited || 'no'
                    ]);
                });

                // Create worksheet
                const ws = XLSX.utils.aoa_to_sheet(wsData);

                // Create workbook
                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'Registrations');

                // Save file
                XLSX.writeFile(wb, 'registration_data_' + new Date().toISOString().slice(0, 10) + '.xlsx');
            }

            // Load data on page load
            loadData();

            // Set up global search functionality
            $('#global-search').on('keyup', function () {
                processAndDisplayData(allRegistrations, this.value);
            });

            // Set up refresh button
            $('#refresh-data').on('click', function () {
                loadData();
            });

            // Set up export button
            $('#export-excel').on('click', function () {
                exportToExcel();
            });
        });
    </script>
</body>

</html>