<?php
session_start();
if (!isset($_SESSION["admin_id"]) || $_SESSION["is_superadmin"] != "yes") {
    header("Location: login.php");
    exit();
}
require_once '../database/connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Reports Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Add Chart.js for visualizations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <!-- Add SheetJS (xlsx) for Excel export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <?php include 'navigation.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Payment Reports Dashboard</h1>
            <div class="flex space-x-2">
                <button id="exportBtn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-file-excel mr-2"></i> Export to Excel
                </button>
            </div>
        </div>

        <?php
        // Query for summary metrics
        $query_total_orders = "SELECT COUNT(order_id) as total_orders FROM orders";
        $query_total_amount = "SELECT SUM(total_amount) as total_amount FROM orders";
        $query_total_users = "SELECT COUNT(DISTINCT user_id) as total_users FROM orders";
        
        $result_total_orders = mysqli_query($conn, $query_total_orders);
        $result_total_amount = mysqli_query($conn, $query_total_amount);
        $result_total_users = mysqli_query($conn, $query_total_users);
        
        $total_orders = mysqli_fetch_assoc($result_total_orders)['total_orders'];
        $total_amount = mysqli_fetch_assoc($result_total_amount)['total_amount'];
        $total_users = mysqli_fetch_assoc($result_total_users)['total_users'];
        $user_value = $total_users * 150;
        ?>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 text-sm font-medium">Total Orders</h3>
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium rounded-full px-2.5 py-0.5">Orders</span>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-3xl font-bold text-gray-800"><?php echo number_format($total_orders); ?></p>
                    <div class="rounded-full bg-blue-100 p-3">
                        <i class="fas fa-shopping-cart text-blue-500"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 text-sm font-medium">Total Revenue</h3>
                    <span class="bg-green-100 text-green-800 text-xs font-medium rounded-full px-2.5 py-0.5">Revenue</span>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-3xl font-bold text-gray-800">₹<?php echo number_format($total_amount, 2); ?></p>
                    <div class="rounded-full bg-green-100 p-3">
                        <i class="fas fa-rupee-sign text-green-500"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 text-sm font-medium">Unique Users</h3>
                    <span class="bg-purple-100 text-purple-800 text-xs font-medium rounded-full px-2.5 py-0.5">Users</span>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-3xl font-bold text-gray-800"><?php echo number_format($total_users); ?></p>
                    <div class="rounded-full bg-purple-100 p-3">
                        <i class="fas fa-users text-purple-500"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-amber-500 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 text-sm font-medium">Users (×150)</h3>
                    <span class="bg-amber-100 text-amber-800 text-xs font-medium rounded-full px-2.5 py-0.5">Entry fee</span>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-3xl font-bold text-gray-800">₹<?php echo number_format($user_value, 2); ?></p>
                    <div class="rounded-full bg-amber-100 p-3">
                        <i class="fas fa-star text-amber-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphs and Tables Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Department Revenue Chart - Now Pie Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Revenue by Department</h2>
                <div class="h-64">
                    <canvas id="departmentChart"></canvas>
                </div>
                <?php
                // Query for department revenue
                $query_dept_revenue = "SELECT d.department_name, SUM(oi.amount) as total_amount
                                       FROM order_items oi
                                       JOIN events e ON oi.event_id = e.event_id
                                       JOIN department d ON e.department_code = d.department_code
                                       GROUP BY d.department_name
                                       ORDER BY total_amount DESC";
                $result_dept_revenue = mysqli_query($conn, $query_dept_revenue);
                
                $dept_names = [];
                $dept_amounts = [];
                
                while ($row = mysqli_fetch_assoc($result_dept_revenue)) {
                    $dept_names[] = $row['department_name'];
                    $dept_amounts[] = $row['total_amount'];
                }
                ?>
                <script>
                    const deptCtx = document.getElementById('departmentChart').getContext('2d');
                    const deptChart = new Chart(deptCtx, {
                        type: 'doughnut', // Changed to pie chart
                        data: {
                            labels: <?php echo json_encode($dept_names); ?>,
                            datasets: [{
                                data: <?php echo json_encode($dept_amounts); ?>,
                                backgroundColor: [
                                    'rgba(59, 130, 246, 0.7)',
                                    'rgba(16, 185, 129, 0.7)',
                                    'rgba(139, 92, 246, 0.7)',
                                    'rgba(245, 158, 11, 0.7)',
                                    'rgba(239, 68, 68, 0.7)',
                                    'rgba(20, 184, 166, 0.7)'
                                ],
                                borderColor: [
                                    'rgb(59, 130, 246)',
                                    'rgb(16, 185, 129)',
                                    'rgb(139, 92, 246)',
                                    'rgb(245, 158, 11)',
                                    'rgb(239, 68, 68)',
                                    'rgb(20, 184, 166)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right'
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.label || '';
                                            if (label) {
                                                label += ': ';
                                            }
                                            label += '₹' + context.raw;
                                            return label;
                                        }
                                    }
                                }
                            }
                        }
                    });
                </script>
            </div>
            
            <!-- Category Revenue Chart - Now Bar Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Revenue by Category</h2>
                <div class="h-64">
                    <canvas id="categoryChart"></canvas>
                </div>
                <?php
                // Query for category revenue
                $query_category_revenue = "SELECT e.category, SUM(oi.amount) as total_amount
                                          FROM order_items oi
                                          JOIN events e ON oi.event_id = e.event_id
                                          GROUP BY e.category
                                          ORDER BY total_amount DESC";
                $result_category_revenue = mysqli_query($conn, $query_category_revenue);
                
                $categories = [];
                $category_amounts = [];
                
                while ($row = mysqli_fetch_assoc($result_category_revenue)) {
                    $categories[] = $row['category'];
                    $category_amounts[] = $row['total_amount'];
                }
                ?>
                <script>
                    const catCtx = document.getElementById('categoryChart').getContext('2d');
                    const catChart = new Chart(catCtx, {
                        type: 'bar', // Changed to bar chart
                        data: {
                            labels: <?php echo json_encode($categories); ?>,
                            datasets: [{
                                label: 'Revenue (₹)',
                                data: <?php echo json_encode($category_amounts); ?>,
                                backgroundColor: [
                                    'rgba(59, 130, 246, 0.7)',
                                    'rgba(16, 185, 129, 0.7)',
                                    'rgba(139, 92, 246, 0.7)',
                                    'rgba(245, 158, 11, 0.7)',
                                    'rgba(239, 68, 68, 0.7)',
                                    'rgba(20, 184, 166, 0.7)'
                                ],
                                borderColor: [
                                    'rgb(59, 130, 246)',
                                    'rgb(16, 185, 129)',
                                    'rgb(139, 92, 246)',
                                    'rgb(245, 158, 11)',
                                    'rgb(239, 68, 68)',
                                    'rgb(20, 184, 166)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return '₹' + value;
                                        }
                                    }
                                }
                            }
                        }
                    });
                </script>
            </div>
        </div>

        <!-- Detailed Reports Section -->
        <div class="space-y-8">
            <!-- Department Revenue Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Department Revenue Breakdown</h2>
                </div>
                <div class="overflow-x-auto">
                    <table id="departmentTable" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Revenue</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">% of Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            // Reset the result pointer to the beginning
                            mysqli_data_seek($result_dept_revenue, 0);
                            $total_dept_amount = array_sum($dept_amounts);
                            
                            while ($row = mysqli_fetch_assoc($result_dept_revenue)) {
                                $percentage = ($total_dept_amount > 0) ? ($row['total_amount'] / $total_dept_amount) * 100 : 0;
                                ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $row['department_name']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">₹<?php echo number_format($row['total_amount'], 2); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right"><?php echo number_format($percentage, 1); ?>%</td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <th scope="row" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th scope="row" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">₹<?php echo number_format($total_dept_amount, 2); ?></th>
                                <th scope="row" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">100.0%</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Event Revenue Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-800">Event Revenue Breakdown</h2>
                    <div class="relative">
                        <input id="eventSearch" class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" type="text" placeholder="Search events...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table id="eventTable" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            // Query for event revenue
                            $query_event_revenue = "SELECT e.event_name, d.department_name, SUM(oi.amount) as total_amount
                                                   FROM order_items oi
                                                   JOIN events e ON oi.event_id = e.event_id
                                                   JOIN department d ON e.department_code = d.department_code
                                                   GROUP BY e.event_name, d.department_name
                                                   ORDER BY total_amount DESC";
                            $result_event_revenue = mysqli_query($conn, $query_event_revenue);
                            
                            while ($row = mysqli_fetch_assoc($result_event_revenue)) {
                                ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $row['event_name']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $row['department_name']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">₹<?php echo number_format($row['total_amount'], 2); ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="text-sm text-gray-700">
                            Showing <span class="font-medium" id="showingCount">all</span> results
                        </span>
                    </div>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50">Previous</button>
                        <button class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700">Next</button>
                    </div>
                </div>
            </div>

            <!-- Category Revenue Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Category Revenue Breakdown</h2>
                </div>
                <div class="overflow-x-auto">
                    <table id="categoryTable" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Revenue</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">% of Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            // Reset the result pointer to the beginning
                            mysqli_data_seek($result_category_revenue, 0);
                            $total_category_amount = array_sum($category_amounts);
                            
                            while ($row = mysqli_fetch_assoc($result_category_revenue)) {
                                $percentage = ($total_category_amount > 0) ? ($row['total_amount'] / $total_category_amount) * 100 : 0;
                                ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $row['category']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">₹<?php echo number_format($row['total_amount'], 2); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right"><?php echo number_format($percentage, 1); ?>%</td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <th scope="row" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th scope="row" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">₹<?php echo number_format($total_category_amount, 2); ?></th>
                                <th scope="row" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">100.0%</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Event search functionality
    document.getElementById('eventSearch').addEventListener('keyup', function() {
        const input = this.value.toLowerCase();
        const table = document.getElementById('eventTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        let visibleCount = 0;
        
        for (let i = 0; i < rows.length; i++) {
            const eventName = rows[i].getElementsByTagName('td')[0].textContent.toLowerCase();
            const department = rows[i].getElementsByTagName('td')[1].textContent.toLowerCase();
            
            if (eventName.includes(input) || department.includes(input)) {
                rows[i].style.display = '';
                visibleCount++;
            } else {
                rows[i].style.display = 'none';
            }
        }
        
        document.getElementById('showingCount').textContent = visibleCount === rows.length ? 'all' : visibleCount;
    });

    // Excel Export Function
    document.getElementById('exportBtn').addEventListener('click', function() {
        exportToExcel();
    });

    function exportToExcel() {
        // Create workbook
        const wb = XLSX.utils.book_new();
        
        // Add department data
        const deptTable = document.getElementById('departmentTable');
        const deptWS = XLSX.utils.table_to_sheet(deptTable);
        XLSX.utils.book_append_sheet(wb, deptWS, "Department Revenue");
        
        // Add event data
        const eventTable = document.getElementById('eventTable');
        const eventWS = XLSX.utils.table_to_sheet(eventTable);
        XLSX.utils.book_append_sheet(wb, eventWS, "Event Revenue");
        
        // Add category data
        const categoryTable = document.getElementById('categoryTable');
        const categoryWS = XLSX.utils.table_to_sheet(categoryTable);
        XLSX.utils.book_append_sheet(wb, categoryWS, "Category Revenue");
        
        // Generate summary data
        const summaryData = [
            ["Payment Reports Summary", ""],
            ["Date", new Date().toLocaleDateString()],
            ["", ""],
            ["Metric", "Value"],
            ["Total Orders", <?php echo $total_orders; ?>],
            ["Total Revenue", "₹<?php echo number_format($total_amount, 2); ?>"],
            ["Unique Users", <?php echo $total_users; ?>],
            ["User Value (×150)", "₹<?php echo number_format($user_value, 2); ?>"],
        ];
        
        const summaryWS = XLSX.utils.aoa_to_sheet(summaryData);
        XLSX.utils.book_append_sheet(wb, summaryWS, "Summary");
        
        // Save the file
        XLSX.writeFile(wb, "Payment_Reports_" + new Date().toISOString().slice(0,10) + ".xlsx");
    }
    </script>
</body>
</html>