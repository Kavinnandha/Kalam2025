<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../header/links.php'; ?>
    <?php include '../header/navbar_styles.php'; ?>
    <title>Departments</title>
</head>

<body class="bg-gradient-to-br from-orange-50 to-yellow-50 pb-16 md:hidden">
    <?php include '../header/navbar.php'; ?>
    
    <div class="pt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header Section with Gradient Text -->
            <div class="sm:flex sm:items-center sm:justify-between mb-12">
                <div class="relative">
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-orange-600 to-yellow-500 text-transparent bg-clip-text">
                        Departments
                    </h1>
                    <div class="absolute -bottom-2 left-0 h-1 w-24 bg-gradient-to-r from-orange-500 to-yellow-400 rounded-full"></div>
                </div>
            </div>

            <div class="mt-8">
                <?php
                    include '../database/connection.php';
                    $sql = "SELECT d.department_code, d.department_name, COUNT(e.event_id) AS event_count 
                            FROM department d
                            JOIN events e ON e.department_code = d.department_code
                            GROUP BY d.department_code, d.department_name";
                    $result = $conn->query($sql);
                ?>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php while($row = $result->fetch_assoc()): ?>
                        <a href="events.php?code=<?php echo urlencode($row['department_code']); ?>" 
                           class="block group cursor-pointer h-full">
                            <div class="bg-gradient-to-br from-orange-100 to-yellow-50 h-full
                                      p-8 rounded-2xl shadow-lg hover:shadow-2xl 
                                      transform transition-all duration-300 hover:-translate-y-1 
                                      border border-orange-100 hover:border-orange-200 
                                      relative overflow-hidden flex flex-col">
                                <!-- Hover overlay effect -->
                                <div class="absolute inset-0 bg-gradient-to-br from-orange-500 to-yellow-400 
                                          opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                                
                                <div class="flex items-center space-x-4 relative flex-grow ">
                                    <div class="flex-shrink-0 h-14 w-14 rounded-xl bg-gradient-to-br 
                                              from-orange-200 to-yellow-100 
                                              flex items-center justify-center 
                                              group-hover:from-orange-300 group-hover:to-yellow-200 
                                              transition-colors duration-300">
                                        <svg class="h-8 w-8 text-orange-600 group-hover:text-orange-700 
                                                  transition-colors duration-300" 
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-xl font-semibold text-gray-800 
                                                   group-hover:text-orange-700 transition-colors 
                                                   duration-300 break-words">
                                            <?php echo htmlspecialchars($row['department_name']); ?>
                                        </h3>
                                        <p class="mt-2 text-sm text-gray-600 font-medium">
                                            Events: <span class="text-orange-600">
                                                <?php echo htmlspecialchars($row['event_count']); ?>
                                            </span>
                                        </p>
                                    </div>
                                    <!-- Arrow indicator -->
                                    <div class="text-orange-500 group-hover:text-orange-600 
                                                transition-colors duration-300 flex-shrink-0 flex items-center">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endwhile; ?>

                    <?php $conn->close(); ?>
                </div>
            </div>
        </div>
    </div>

</body>

</html>