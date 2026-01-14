<?php
require_once 'config_portfolio.php';

// Handle mark as read
if (isset($_GET['read'])) {
    $id = intval($_GET['read']);
    $sql = "UPDATE kontak SET status = 'read' WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Handle delete message
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM kontak WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Get all messages
$sql = "SELECT * FROM kontak ORDER BY tanggal DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox - TechRestore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #0a0a0a; color: #ffffff; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .glass-card { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body>
    <header class="gradient-bg shadow-lg">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Message Inbox</h1>
                <a href="admin_portfolio.php" class="bg-white text-purple-600 px-4 py-2 rounded-lg hover:bg-gray-100">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">
        <div class="glass-card rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/10">
                <h2 class="text-2xl font-bold">Contact Messages</h2>
            </div>
            <div class="divide-y divide-white/10">
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while($message = mysqli_fetch_assoc($result)): ?>
                    <div class="p-6 hover:bg-white/5 <?php echo $message['status'] == 'unread' ? 'bg-purple-500/10' : ''; ?>">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="text-lg font-bold"><?php echo htmlspecialchars($message['nama']); ?></h3>
                                <p class="text-gray-400 text-sm"><?php echo htmlspecialchars($message['email']); ?></p>
                            </div>
                            <div class="text-right">
                                <p class="text-gray-400 text-sm"><?php echo date('d M Y, H:i', strtotime($message['tanggal'])); ?></p>
                                <span class="inline-block px-3 py-1 mt-2 rounded-full text-xs font-semibold
                                    <?php echo $message['status'] == 'unread' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-green-500/20 text-green-400'; ?>">
                                    <?php echo ucfirst($message['status']); ?>
                                </span>
                            </div>
                        </div>
                        <p class="text-gray-300 mb-4"><?php echo nl2br(htmlspecialchars($message['pesan'])); ?></p>
                        <div class="flex space-x-4">
                            <?php if ($message['status'] == 'unread'): ?>
                            <a href="?read=<?php echo $message['id']; ?>" class="text-green-400 hover:text-green-300">
                                Mark as Read
                            </a>
                            <?php endif; ?>
                            <a href="mailto:<?php echo $message['email']; ?>" class="text-purple-400 hover:text-purple-300">
                                Reply via Email
                            </a>
                            <a href="?delete=<?php echo $message['id']; ?>" 
                               onclick="return confirm('Delete this message?')" 
                               class="text-red-400 hover:text-red-300">
                                Delete
                            </a>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="p-12 text-center text-gray-400">
                        <p>No messages yet</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>