<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../header/links.php'; ?>
    <?php include '../header/navbar_styles.php'; ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #ff5e62 0%, #ff9966 100%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            box-shadow: 0 8px 32px rgba(255, 94, 98, 0.1);
        }
        .btn-gradient {
            background: linear-gradient(135deg, #ff5e62 0%, #ff9966 100%);
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 94, 98, 0.2);
        }
        /* Enhanced form styling */
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid rgba(209, 213, 219, 0.5);
            border-radius: 0.5rem;
            background-color: rgba(255, 255, 255, 0.8);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            font-size: 1rem;
        }
        .form-input:focus {
            border-color: #ff7e65;
            box-shadow: 0 0 0 3px rgba(255, 126, 101, 0.2);
            outline: none;
        }
        .form-input::placeholder {
            color: #9ca3af;
            opacity: 0.7;
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #4b5563;
            font-size: 0.95rem;
            letter-spacing: 0.025em;
        }
        .input-group {
            margin-bottom: 1.5rem;
            position: relative;
        }
        .input-group-icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 1rem;
            color: #9ca3af;
        }
        .input-with-icon {
            padding-left: 2.5rem;
        }
        
        /* Responsive styles */
        @media (max-width: 640px) {
            .responsive-flex {
                flex-direction: column;
                gap: 0.75rem;
            }
            
            .responsive-button-group {
                display: flex;
                gap: 0.5rem;
                margin-top: 0.75rem;
            }
            
            .responsive-button-group button {
                flex: 1;
                justify-content: center;
            }
            
            .responsive-table {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .header-actions {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }
            
            .header-actions button {
                align-self: flex-start;
            }
            
            .team-actions {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
            }
            
            .team-actions a,
            .team-actions form {
                width: 100%;
            }
            
            .team-actions button {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <?php include '../header/navbar.php'; ?>
    <div class="pt-20"></div>

    <?php
    
    // Database connection
    include_once '../database/connection.php';
    // Get the user ID from the session
 
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit();
    }
    $user_id = $_SESSION['user_id'];

    if (isset($_GET['event_id'])) {
        $event_id = $_GET['event_id'];

        // User and event verification
        $verify_query = "SELECT * FROM orders o JOIN order_items oi ON oi.order_id = o.order_id JOIN events e ON e.event_id = oi.event_id WHERE o.user_id = ? AND e.category = 'Culturals' AND e.event_id = ?";
        $verify_stmt = $conn->prepare($verify_query);
        $verify_stmt->bind_param("ii", $user_id, $event_id);
        $verify_stmt->execute();
        $verify_result = $verify_stmt->get_result();
        if ($verify_result->num_rows == 0){
            echo "<script>window.location.href = '../index.php';</script>";
            exit();
        } 
    } else {
        $event_id = $_POST['event_id'];
    }

    // Check if user is already in a team for this event
    $checkTeamSql = "SELECT c.cteam_id, c.cteam_name
                    FROM culturals c 
                    JOIN culturals_team ct ON c.cteam_id = ct.cteam_id 
                    WHERE ct.user_id = ? AND c.event_id = ?";
    $checkTeamStmt = $conn->prepare($checkTeamSql);
    $checkTeamStmt->bind_param("ii", $user_id, $event_id);
    $checkTeamStmt->execute();
    $teamResult = $checkTeamStmt->get_result();
    
    // Process form submissions
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Create new team
        if (isset($_POST['create_team'])) {
            $event_id = $_POST['event_id'];
            $team_name = trim($_POST['team_name']);
            
            // Insert into culturals table
            $createTeamSql = "INSERT INTO culturals (cteam_name, event_id) VALUES (?, ?)";
            $createTeamStmt = $conn->prepare($createTeamSql);
            $createTeamStmt->bind_param("si", $team_name, $event_id);
            
            if ($createTeamStmt->execute()) {
                $cteam_id = $conn->insert_id;
                
                // Add user to the team
                $addMemberSql = "INSERT INTO culturals_team (cteam_id, user_id) VALUES (?, ?)";
                $addMemberStmt = $conn->prepare($addMemberSql);
                $addMemberStmt->bind_param("ii", $cteam_id, $user_id);
                $addMemberStmt->execute();
                
                // Refresh the page to show updated info
                echo "<script>window.location.href = '?event_id=" . $event_id . "&team_created=1';</script>";
                exit();
            }
        }
        
        // Add team member
        if (isset($_POST['add_member'])) {
            $cteam_id = $_POST['cteam_id'];
            $email = trim($_POST['member_email']);
            
            // Get user ID for the email
            $userIdSql = "SELECT user_id FROM users WHERE email = ?";
            $userIdStmt = $conn->prepare($userIdSql);
            $userIdStmt->bind_param("s", $email);
            $userIdStmt->execute();
            $userIdResult = $userIdStmt->get_result();
            
            if ($userIdResult->num_rows > 0) {
                $userData = $userIdResult->fetch_assoc();
                $newMemberId = $userData['user_id'];
                
                // Check if user is already in this team
                $checkExistingSql = "SELECT * FROM culturals_team WHERE cteam_id = ? AND user_id = ?";
                $checkExistingStmt = $conn->prepare($checkExistingSql);
                $checkExistingStmt->bind_param("ii", $cteam_id, $newMemberId);
                $checkExistingStmt->execute();
                $checkExistingResult = $checkExistingStmt->get_result();
                
                if ($checkExistingResult->num_rows > 0) {
                    $error = "This user is already in your team.";
                } else {
                    // Count current team members
                    $countMembersSql = "SELECT COUNT(*) as member_count FROM culturals_team WHERE cteam_id = ?";
                    $countMembersStmt = $conn->prepare($countMembersSql);
                    $countMembersStmt->bind_param("i", $cteam_id);
                    $countMembersStmt->execute();
                    $countResult = $countMembersStmt->get_result();
                    $countData = $countResult->fetch_assoc();
                    
                    if ($countData['member_count'] >= 15) {
                        $error = "Maximum team size (15 members) reached.";
                    } else {
                        // Check if the user has purchased the event
                        $checkPurchaseSql = "SELECT * FROM orders o JOIN order_items oi ON o.order_id = oi.order_id WHERE o.user_id = ? AND oi.event_id = ?";
                        $checkPurchaseStmt = $conn->prepare($checkPurchaseSql);
                        $checkPurchaseStmt->bind_param("ii", $newMemberId, $event_id);
                        $checkPurchaseStmt->execute();
                        $purchaseResult = $checkPurchaseStmt->get_result();
                        
                        if ($purchaseResult->num_rows == 0) {
                            $error = "This user has not purchased the event. They must register for the event before joining your team.";
                        } else {
                            // Add member to team
                            $addMemberSql = "INSERT INTO culturals_team (cteam_id, user_id) VALUES (?, ?)";
                            $addMemberStmt = $conn->prepare($addMemberSql);
                            $addMemberStmt->bind_param("ii", $cteam_id, $newMemberId);
                            $addMemberStmt->execute();
                            $success = "Team member added successfully!";
                        }
                    }
                }
            } else {
                $error = "No user found with that email.";
            }
        }
        
        // Remove team member
        if (isset($_POST['remove_member'])) {
            $cteam_id = $_POST['cteam_id'];
            $member_id = $_POST['member_id'];
            
            // Don't allow removing yourself if you're the only member
            if ($member_id == $user_id) {
                $countMembersSql = "SELECT COUNT(*) as member_count FROM culturals_team WHERE cteam_id = ?";
                $countMembersStmt = $conn->prepare($countMembersSql);
                $countMembersStmt->bind_param("i", $cteam_id);
                $countMembersStmt->execute();
                $countResult = $countMembersStmt->get_result();
                $countData = $countResult->fetch_assoc();
                
                if ($countData['member_count'] <= 1) {
                    $error = "You cannot remove yourself as the only team member. Delete the team instead.";
                } else {
                    $removeMemberSql = "DELETE FROM culturals_team WHERE cteam_id = ? AND user_id = ?";
                    $removeMemberStmt = $conn->prepare($removeMemberSql);
                    $removeMemberStmt->bind_param("ii", $cteam_id, $member_id);
                    $removeMemberStmt->execute();
                    $success = "Team member removed successfully!";
                }
            } else {
                $removeMemberSql = "DELETE FROM culturals_team WHERE cteam_id = ? AND user_id = ?";
                $removeMemberStmt = $conn->prepare($removeMemberSql);
                $removeMemberStmt->bind_param("ii", $cteam_id, $member_id);
                $removeMemberStmt->execute();
                $success = "Team member removed successfully!";
            }
        }
        
        // Update team details
        if (isset($_POST['update_team'])) {
            $cteam_id = $_POST['cteam_id'];
            $team_name = trim($_POST['team_name']);
            
            $updateTeamSql = "UPDATE culturals SET cteam_name = ? WHERE cteam_id = ?";
            $updateTeamStmt = $conn->prepare($updateTeamSql);
            $updateTeamStmt->bind_param("si", $team_name, $cteam_id);
            $updateTeamStmt->execute();
            $success = "Team details updated successfully!";
        }
        
        // Delete team
        if (isset($_POST['delete_team'])) {
            $cteam_id = $_POST['cteam_id'];
            
            // Delete team members first (due to foreign key constraint)
            $deleteTeamMembersSql = "DELETE FROM culturals_team WHERE cteam_id = ?";
            $deleteTeamMembersStmt = $conn->prepare($deleteTeamMembersSql);
            $deleteTeamMembersStmt->bind_param("i", $cteam_id);
            $deleteTeamMembersStmt->execute();
            
            // Then delete the team
            $deleteTeamSql = "DELETE FROM culturals WHERE cteam_id = ?";
            $deleteTeamStmt = $conn->prepare($deleteTeamSql);
            $deleteTeamStmt->bind_param("i", $cteam_id);
            $deleteTeamStmt->execute();
            
            echo "<script>window.location.href = '../categories/event_details.php?event_id=$event_id';</script>";
            exit();
        }
    }
    ?>

    <div class="container mx-auto px-4 py-8">
        <div class="gradient-bg text-white p-8 rounded-t-xl shadow-lg">
            <h1 class="text-3xl font-bold mb-2">Cultural Team Management</h1>
            <p class="text-lg opacity-90">
                <?php
                // Get event name
                $eventSql = "SELECT event_name FROM events WHERE event_id = ?";
                $eventStmt = $conn->prepare($eventSql);
                $eventStmt->bind_param("i", $event_id);
                $eventStmt->execute();
                $eventResult = $eventStmt->get_result();
                
                if ($eventResult->num_rows > 0) {
                    $eventData = $eventResult->fetch_assoc();
                    echo htmlspecialchars($eventData['event_name']);
                } else {
                    echo "Event";
                }
                ?>
            </p>
        </div>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p><?php echo $success; ?></p>
            </div>
        <?php endif; ?>

        <?php if ($teamResult->num_rows > 0): ?>
            <?php 
            $teamData = $teamResult->fetch_assoc();
            $cteam_id = $teamData['cteam_id'];
            
            // Get team members
            $membersSql = "SELECT u.user_id, u.name, u.email, u.phone 
                          FROM users u 
                          JOIN culturals_team ct ON u.user_id = ct.user_id 
                          WHERE ct.cteam_id = ?";
            $membersStmt = $conn->prepare($membersSql);
            $membersStmt->bind_param("i", $cteam_id);
            $membersStmt->execute();
            $membersResult = $membersStmt->get_result();
            $members = $membersResult->fetch_all(MYSQLI_ASSOC);
            $member_count = count($members);
            ?>

            <div class="glass-card p-6 mb-8">
                <div class="flex justify-between items-center mb-6 header-actions">
                    <h2 class="text-2xl font-bold text-gray-800">Your Team: <?php echo htmlspecialchars($teamData['cteam_name']); ?></h2>
                    <button class="text-orange-500 hover:text-orange-700 transition-colors duration-300 flex items-center" onclick="toggleEditTeam()">
                        <i class="fas fa-edit mr-1"></i> Edit Team Details
                    </button>
                </div>
                
                <div id="team-details" class="mb-6">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-700">Team Name</h3>
                        <p class="text-gray-700"><?php echo htmlspecialchars($teamData['cteam_name']); ?></p>
                    </div>
                </div>
                
                <div id="edit-team-form" class="mb-6 hidden">
                    <form method="POST" class="space-y-4">
                        <input type="hidden" name="cteam_id" value="<?php echo $cteam_id; ?>">
                        <div class="input-group">
                            <label for="team_name" class="form-label">Team Name</label>
                            <div class="relative">
                                <i class="fas fa-users input-group-icon"></i>
                                <input type="text" id="team_name" name="team_name" value="<?php echo htmlspecialchars($teamData['cteam_name']); ?>" required 
                                    class="form-input input-with-icon">
                            </div>
                        </div>
                        <div class="flex gap-3 responsive-flex">
                            <button type="submit" name="update_team" class="btn-gradient text-white px-4 py-2 rounded-md flex items-center">
                                <i class="fas fa-save mr-2"></i> Save Changes
                            </button>
                            <button type="button" onclick="toggleEditTeam()" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 transition-colors duration-300 flex items-center">
                                <i class="fas fa-times mr-2"></i> Cancel
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="mt-8">
                    <div class="flex justify-between items-center mb-4 header-actions">
                        <h3 class="text-xl font-bold text-gray-800">Team Members (<?php echo $member_count; ?>/15)</h3>
                        <?php if ($member_count < 15): ?>
                            <button class="text-orange-500 hover:text-orange-700 transition-colors duration-300 flex items-center" onclick="toggleAddMember()">
                                <i class="fas fa-plus-circle mr-1"></i> Add Member
                            </button>
                        <?php endif; ?>
                    </div>
                    
                    <div id="add-member-form" class="mb-6 hidden">
                        <form method="POST">
                            <input type="hidden" name="cteam_id" value="<?php echo $cteam_id; ?>">
                            <div class="mb-3">
                                <div class="relative">
                                    <i class="fas fa-envelope input-group-icon"></i>
                                    <input type="email" name="member_email" placeholder="Enter team member's email" required 
                                        class="form-input input-with-icon">
                                </div>
                            </div>
                            <div class="flex gap-2 responsive-button-group">
                                <button type="submit" name="add_member" class="btn-gradient text-white px-4 py-2 rounded-md flex items-center">
                                    <i class="fas fa-user-plus mr-2"></i> Add
                                </button>
                                <button type="button" onclick="toggleAddMember()" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 transition-colors duration-300 flex items-center">
                                    <i class="fas fa-times mr-2"></i> Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="overflow-x-auto responsive-table">
                        <table class="min-w-full bg-white rounded-lg overflow-hidden">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($members as $member): ?>
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <?php echo htmlspecialchars($member['name']); ?>
                                        <?php if ($member['user_id'] == $user_id): ?>
                                            <span class="text-xs text-orange-500 ml-2">(You)</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($member['email']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($member['phone']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <form method="POST" class="inline">
                                            <input type="hidden" name="cteam_id" value="<?php echo $cteam_id; ?>">
                                            <input type="hidden" name="member_id" value="<?php echo $member['user_id']; ?>">
                                            <button type="submit" name="remove_member" class="text-red-600 hover:text-red-900 transition-colors duration-300" 
                                                <?php if ($member_count <= 1 && $member['user_id'] == $user_id): ?>disabled<?php endif; ?>>
                                                <i class="fas fa-user-minus"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="mt-10 border-t pt-6 flex justify-between team-actions">
                    <a href="../categories/event_details.php?event_id=<?php echo $event_id; ?>" class="inline-flex items-center text-gray-700 hover:text-gray-900 transition-colors duration-300">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Cultural Events
                    </a>
                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this team? This action cannot be undone.')">
                        <input type="hidden" name="cteam_id" value="<?php echo $cteam_id; ?>">
                        <button type="submit" name="delete_team" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md inline-flex items-center transition-colors duration-300">
                            <i class="fas fa-trash-alt mr-2"></i> Delete Team
                        </button>
                    </form>
                </div>
            </div>

        <?php else: ?>
            <div class="glass-card p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Create a New Cultural Team</h2>
                <form method="POST" class="space-y-6">
                    <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                    <div class="input-group">
                        <label for="team_name" class="form-label">Team Name</label>
                        <div class="relative">
                            <i class="fas fa-users input-group-icon"></i>
                            <input type="text" id="team_name" name="team_name" placeholder="Enter your team name" required 
                                class="form-input input-with-icon">
                        </div>
                    </div>
                    <div class="flex justify-between responsive-flex">
                        <a href="../categories/event_details.php?event_id=<?php echo $event_id; ?>" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 inline-flex items-center transition-colors duration-300">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Cultural Events
                        </a>
                        <button type="submit" name="create_team" class="btn-gradient text-white px-6 py-2 rounded-md flex items-center">
                            <i class="fas fa-plus-circle mr-2"></i> Create Team
                        </button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function toggleEditTeam() {
            const detailsEl = document.getElementById('team-details');
            const formEl = document.getElementById('edit-team-form');
            detailsEl.classList.toggle('hidden');
            formEl.classList.toggle('hidden');
        }
        
        function toggleAddMember() {
            const formEl = document.getElementById('add-member-form');
            formEl.classList.toggle('hidden');
        }
    </script>

</body>
</html>