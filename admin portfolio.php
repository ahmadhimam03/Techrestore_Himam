<?php
require_once 'config_portfolio.php';

// Handle upload portfolio
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_portfolio') {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    
    // Handle file upload
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $file = $_FILES['gambar'];
        $fileName = time() . '_' . basename($file['name']);
        $targetPath = UPLOAD_DIR . $fileName;
        
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $sql = "INSERT INTO portfolio (judul, deskripsi, kategori, gambar, tanggal) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssss", $judul, $deskripsi, $kategori, $fileName, $tanggal);
            
            if (mysqli_stmt_execute($stmt)) {
                $success_message = "Portfolio berhasil ditambahkan!";
            } else {
                $error_message = "Gagal menyimpan ke database!";
            }
            mysqli_stmt_close($stmt);
        } else {
            $error_message = "Gagal upload gambar!";
        }
    }
}

// Handle delete portfolio
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Get image filename
    $sql = "SELECT gambar FROM portfolio WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        // Delete image file
        $imagePath = UPLOAD_DIR . $row['gambar'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        
        // Delete from database
        $sql = "DELETE FROM portfolio WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Portfolio berhasil dihapus!";
        }
    }
}

// Get all portfolio
$sql = "SELECT * FROM portfolio ORDER BY tanggal DESC";
$result_portfolio = mysqli_query($conn, $sql);

// Get statistics
$stats = [
    'portfolio' => mysqli_num_rows($result_portfolio),
    'testimoni' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM testimoni"))['total'],
    'pesan' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM kontak WHERE status='unread'"))['total']
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portfolio - TechRestore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #0a0a0a; color: #ffffff; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .glass-card { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="font-sans">
    <!-- Header -->
    <header class="gradient-bg shadow-lg">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Admin Dashboard - Portfolio</h1>
                <div class="space-x-4">
                    <a href="index.php" class="bg-white text-purple-600 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                        View Website
                    </a>
                    <a href="inbox.php" class="bg-white/10 text-white px-4 py-2 rounded-lg hover:bg-white/20 transition">
                        Inbox (<?php echo $stats['pesan']; ?>)
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">
        <!-- Statistics -->
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <div class="glass-card p-6 rounded-xl">
                <h3 class="text-gray-400 text-sm font-semibold mb-2">Total Portfolio</h3>
                <p class="text-4xl font-bold text-purple-400"><?php echo $stats['portfolio']; ?></p>
            </div>
            <div class="glass-card p-6 rounded-xl">
                <h3 class="text-gray-400 text-sm font-semibold mb-2">Total Testimoni</h3>
                <p class="text-4xl font-bold text-green-400"><?php echo $stats['testimoni']; ?></p>
            </div>
            <div class="glass-card p-6 rounded-xl">
                <h3 class="text-gray-400 text-sm font-semibold mb-2">Unread Messages</h3>
                <p class="text-4xl font-bold text-yellow-400"><?php echo $stats['pesan']; ?></p>
            </div>
        </div>

        <?php if (isset($success_message)): ?>
        <div class="bg-green-500/20 border border-green-500 text-green-200 px-6 py-4 rounded-xl mb-6">
            <?php echo $success_message; ?>
        </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
        <div class="bg-red-500/20 border border-red-500 text-red-200 px-6 py-4 rounded-xl mb-6">
            <?php echo $error_message; ?>
        </div>
        <?php endif; ?>

        <!-- Add Portfolio Form -->
        <div class="glass-card p-8 rounded-xl mb-8">
            <h2 class="text-2xl font-bold mb-6">Tambah Portfolio Baru</h2>
            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="action" value="add_portfolio">
                
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Judul</label>
                        <input type="text" name="judul" required class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg focus:outline-none focus:border-purple-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold mb-2">Kategori</label>
                        <select name="kategori" required class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg focus:outline-none focus:border-purple-500">
                            <option value="">Pilih Kategori</option>
                            <option value="Screen Repair">Screen Repair</option>
                            <option value="Battery Service">Battery Service</option>
                            <option value="Hardware Repair">Hardware Repair</option>
                            <option value="Software Fix">Software Fix</option>
                            <option value="Water Damage">Water Damage</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" required class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg focus:outline-none focus:border-purple-500"></textarea>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Gambar</label>
                        <input type="file" name="gambar" accept="image/*" required class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg focus:outline-none focus:border-purple-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold mb-2">Tanggal</label>
                        <input type="date" name="tanggal" required class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg focus:outline-none focus:border-purple-500">
                    </div>
                </div>

                <button type="submit" class="bg-gradient-to-r from-purple-600 to-pink-500 px-8 py-3 rounded-lg font-bold hover:shadow-lg transition">
                    Tambah Portfolio
                </button>
            </form>
        </div>

        <!-- Portfolio List -->
        <div class="glass-card rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/10">
                <h2 class="text-2xl font-bold">Daftar Portfolio</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold">ID</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Gambar</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Judul</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Kategori</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Tanggal</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        <?php while($portfolio = mysqli_fetch_assoc($result_portfolio)): ?>
                        <tr class="hover:bg-white/5">
                            <td class="px-6 py-4"><?php echo $portfolio['id']; ?></td>
                            <td class="px-6 py-4">
                                <img src="uploads/<?php echo $portfolio['gambar']; ?>" alt="" class="w-16 h-16 object-cover rounded-lg">
                            </td>
                            <td class="px-6 py-4 font-semibold"><?php echo htmlspecialchars($portfolio['judul']); ?></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-purple-500/20 text-purple-300 rounded-full text-sm">
                                    <?php echo $portfolio['kategori']; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4"><?php echo date('d M Y', strtotime($portfolio['tanggal'])); ?></td>
                            <td class="px-6 py-4">
                                <a href="?delete=<?php echo $portfolio['id']; ?>" 
                                   onclick="return confirm('Yakin ingin menghapus?')" 
                                   class="text-red-400 hover:text-red-300">
                                    Hapus
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>