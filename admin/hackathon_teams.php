<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events & Hackathon Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <?php
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Get department from session
    $department_code = isset($_SESSION['department_code']) ? $_SESSION['department_code'] : null;
    
    // Database connection
    require_once '../database/connection.php';
    
    // Function to fetch events
    function getEvents($conn, $department_code = null) {
        $sql = "SELECT e.* FROM events e WHERE e.category = 'Hackathon'";
        
        if ($department_code) {
            $sql .= " AND e.department_code = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $department_code);
        } else {
            $stmt = $conn->prepare($sql);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $events = [];
        
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
        
        $stmt->close();
        return $events;
    }
    
    // Function to fetch hackathon teams by event with user details
    function getHackathonTeamsByEvent($conn, $event_id) {
        $sql = "SELECT h.*, ht.user_id, ht.team_member_id, u.name, u.email, u.phone
                FROM hackathon h
                JOIN hackathon_teams ht ON h.team_id = ht.team_id 
                JOIN events e ON e.event_id = h.event_id
                LEFT JOIN users u ON ht.user_id = u.user_id
                WHERE e.category = 'Hackathon' AND h.event_id = ?
                ORDER BY h.team_name, ht.team_member_id";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $teams = [];
        while ($row = $result->fetch_assoc()) {
            $team_id = $row['team_id'];
            
            if (!isset($teams[$team_id])) {
                $teams[$team_id] = [
                    'team_id' => $team_id,
                    'team_name' => $row['team_name'],
                    'title' => $row['title'],
                    'problem_statement' => $row['problem_statement'],
                    'members' => []
                ];
            }
            
            if ($row['user_id']) {
                $teams[$team_id]['members'][] = [
                    'user_id' => $row['user_id'],
                    'team_member_id' => $row['team_member_id'],
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'phone' => $row['phone']
                ];
            }
        }
        
        $stmt->close();
        return $teams;
    }
    
    // Get all events (filtered by department if needed)
    $events = getEvents($conn, $department_code);
    ?>

    <div class="container mx-auto px-4 py-8">
        <header class="mb-8">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold text-indigo-700">Events & Hackathon Dashboard</h1>
                <?php if ($department_code): ?>
                    <span class="px-4 py-2 bg-indigo-100 text-indigo-800 rounded-lg">
                        Department: <?php echo htmlspecialchars($department_code); ?>
                    </span>
                <?php else: ?>
                    <span class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg">
                        All Departments
                    </span>
                <?php endif; ?>
            </div>
        </header>

        <div class="grid grid-cols-1 gap-8">
            <?php foreach ($events as $event): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-indigo-600 px-6 py-4">
                        <h2 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            <?php echo htmlspecialchars($event['event_name']); ?>
                            <span class="ml-3 px-2 py-1 text-xs bg-indigo-200 text-indigo-800 rounded">
                                <?php echo htmlspecialchars($event['category']); ?>
                            </span>
                        </h2>
                        <p class="text-indigo-200 text-sm">
                            Department: <?php echo htmlspecialchars($event['department_code']); ?>
                        </p>
                    </div>
                    
                    <?php 
                    // Get teams for this event with user details
                    $teams = getHackathonTeamsByEvent($conn, $event['event_id']); 
                    ?>
                    
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">
                            <?php echo count($teams) > 0 ? 'Participating Teams (' . count($teams) . ')' : 'No teams registered yet'; ?>
                        </h3>
                        
                        <?php if (count($teams) > 0): ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <?php foreach ($teams as $team): ?>
                                    <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                                        <div class="bg-indigo-50 px-4 py-3">
                                            <div class="flex justify-between items-start">
                                                <h4 class="text-indigo-700 font-medium">
                                                    <?php echo htmlspecialchars($team['team_name']); ?>
                                                </h4>
                                                <span class="text-xs text-gray-500">
                                                    ID: <?php echo $team['team_id']; ?>
                                                </span>
                                            </div>
                                            
                                            <p class="text-sm font-medium text-gray-700 mt-1">
                                                <?php echo htmlspecialchars($team['title']); ?>
                                            </p>
                                        </div>
                                        
                                        <div class="px-4 py-3 border-b border-gray-100">
                                            <p class="text-sm text-gray-600">
                                                <span class="font-medium">Problem Statement:</span> 
                                                <?php echo htmlspecialchars($team['problem_statement']); ?>
                                            </p>
                                        </div>
                                        
                                        <div class="px-4 py-3">
                                            <h5 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                                <i class="fas fa-users mr-2 text-indigo-500"></i>
                                                Team Members (<?php echo count($team['members']); ?>)
                                            </h5>
                                            
                                            <?php if (!empty($team['members'])): ?>
                                                <div class="space-y-3">
                                                    <?php foreach ($team['members'] as $member): ?>
                                                        <div class="flex items-start text-sm border-b border-gray-100 pb-2">
                                                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 flex-shrink-0 mr-3">
                                                                <?php echo substr($member['name'] ?? $member['user_id'], 0, 1); ?>
                                                            </div>
                                                            <div class="flex-grow">
                                                                <p class="font-medium"><?php echo htmlspecialchars($member['name'] ?? 'N/A'); ?></p>
                                                                <div class="text-xs text-gray-500 mt-1">
                                                                    <?php if (!empty($member['email'])): ?>
                                                                        <p class="flex items-center">
                                                                            <i class="fas fa-envelope mr-1"></i>
                                                                            <?php echo htmlspecialchars($member['email']); ?>
                                                                        </p>
                                                                    <?php endif; ?>
                                                                    
                                                                    <?php if (!empty($member['phone'])): ?>
                                                                        <p class="flex items-center mt-1">
                                                                            <i class="fas fa-phone mr-1"></i>
                                                                            <?php echo htmlspecialchars($member['phone']); ?>
                                                                        </p>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php else: ?>
                                                <p class="text-sm text-gray-500">No team members registered</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <?php if (count($events) === 0): ?>
                <div class="text-center py-12">
                    <i class="fas fa-calendar-times text-gray-400 text-5xl mb-4"></i>
                    <p class="text-xl text-gray-600">No events found</p>
                    <?php if ($department_code): ?>
                        <p class="text-gray-500 mt-2">No events are currently registered for department: <?php echo htmlspecialchars($department_code); ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php
    // Close database connection
    $conn->close();
    ?>
</body>
</html>