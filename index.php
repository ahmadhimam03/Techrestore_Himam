<?php
require_once 'config_portfolio.php';

// Ambil data portfolio
$sql_portfolio = "SELECT * FROM portfolio ORDER BY tanggal DESC LIMIT 6";
$result_portfolio = mysqli_query($conn, $sql_portfolio);

// Ambil data testimoni
$sql_testimoni = "SELECT * FROM testimoni WHERE status='active' ORDER BY tanggal DESC LIMIT 3";
$result_testimoni = mysqli_query($conn, $sql_testimoni);

// Ambil statistik
$total_projects = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM portfolio"))['total'];
$total_customers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM testimoni"))['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio TechRestore - Professional Phone Repair Service</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            scroll-behavior: smooth;
        }
        
        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: #0a0a0a;
            color: #ffffff;
        }

        /* Hero Background */
        .hero-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('background.jpg') center/cover;
            opacity: 0.15;
            z-index: 0;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Card Hover Effect */
        .portfolio-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .portfolio-card:hover {
            transform: translateY(-15px) scale(1.02);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.4);
            border-color: rgba(102, 126, 234, 0.5);
        }

        /* Glass Effect */
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Animated Background */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #1a1a1a;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 5px;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(5px);
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: rgba(26, 26, 26, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            max-width: 800px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                transform: translateY(100px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Button Glow */
        .btn-glow {
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.5);
            transition: all 0.3s ease;
        }

        .btn-glow:hover {
            box-shadow: 0 0 30px rgba(102, 126, 234, 0.8);
            transform: scale(1.05);
        }

        /* Stats Counter Animation */
        .stat-number {
            font-size: 3rem;
            font-weight: bold;
            background: linear-gradient(135deg, #667eea, #f093fb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="fixed w-full top-0 z-50 glass-card">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <img src="logo.jpeg" alt="TechRestore Logo" class="w-12 h-12 object-contain rounded-full">
                    <span class="text-2xl font-bold gradient-text">TechRestore</span>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="#home" class="hover:text-purple-400 transition">Home</a>
                    <a href="#portfolio" class="hover:text-purple-400 transition">Portfolio</a>
                    <a href="#services" class="hover:text-purple-400 transition">Services</a>
                    <a href="#testimoni" class="hover:text-purple-400 transition">Testimoni</a>
                    <a href="#contact" class="hover:text-purple-400 transition">Contact</a>
                </div>
                <a href="#contact" class="bg-gradient-to-r from-purple-600 to-pink-500 px-6 py-2 rounded-full font-semibold btn-glow">
                    Get Started
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-bg min-h-screen flex items-center pt-20">
        <div class="hero-content container mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="text-center md:text-left">
                    <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
                        Expert Phone<br>
                        <span class="text-white">Repair Service</span>
                    </h1>
                    <p class="text-xl text-purple-100 mb-8">
                        Professional repair solutions with guaranteed quality and fast turnaround time
                    </p>
                    <div class="flex flex-wrap gap-4 justify-center md:justify-start">
                        <a href="#portfolio" class="bg-white text-purple-600 px-8 py-4 rounded-full font-bold text-lg hover:bg-gray-100 transition">
                            View Portfolio
                        </a>
                        <a href="#contact" class="border-2 border-white text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-white hover:text-purple-600 transition">
                            Contact Us
                        </a>
                    </div>
                </div>
                <div class="float-animation">
                    <img src="logo.jpeg" alt="Phone Repair" class="w-full max-w-md mx-auto opacity-90">
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 glass-card">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="stat-number"><?php echo $total_projects; ?>+</div>
                    <p class="text-gray-400 text-lg">Projects Completed</p>
                </div>
                <div>
                    <div class="stat-number"><?php echo $total_customers; ?>+</div>
                    <p class="text-gray-400 text-lg">Happy Customers</p>
                </div>
                <div>
                    <div class="stat-number">10+</div>
                    <p class="text-gray-400 text-lg">Years Experience</p>
                </div>
                <div>
                    <div class="stat-number">98%</div>
                    <p class="text-gray-400 text-lg">Success Rate</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio Section -->
    <section id="portfolio" class="py-20">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-5xl font-bold mb-4">
                    Our <span class="gradient-text">Portfolio</span>
                </h2>
                <p class="text-gray-400 text-xl">Recent repair projects we've completed</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php while($portfolio = mysqli_fetch_assoc($result_portfolio)): ?>
                <div class="portfolio-card rounded-2xl overflow-hidden cursor-pointer" onclick="openModal(<?php echo $portfolio['id']; ?>)">
                    <div class="aspect-square overflow-hidden">
                        <img src="uploads/<?php echo $portfolio['gambar']; ?>" alt="<?php echo htmlspecialchars($portfolio['judul']); ?>" class="w-full h-full object-cover hover:scale-110 transition duration-500">
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-purple-400 text-sm font-semibold"><?php echo htmlspecialchars($portfolio['kategori']); ?></span>
                            <span class="text-gray-400 text-sm"><?php echo date('M Y', strtotime($portfolio['tanggal'])); ?></span>
                        </div>
                        <h3 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($portfolio['judul']); ?></h3>
                        <p class="text-gray-400"><?php echo htmlspecialchars(substr($portfolio['deskripsi'], 0, 100)); ?>...</p>
                        <button class="mt-4 text-purple-400 font-semibold hover:text-purple-300">
                            View Details →
                        </button>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>

            <div class="text-center mt-12">
                <a href="admin_portfolio.php" class="inline-block bg-gradient-to-r from-purple-600 to-pink-500 px-8 py-4 rounded-full font-bold text-lg btn-glow">
                    View All Projects
                </a>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 glass-card">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-5xl font-bold mb-4">
                    Our <span class="gradient-text">Services</span>
                </h2>
                <p class="text-gray-400 text-xl">Professional repair solutions for all devices</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="glass-card p-8 rounded-2xl hover:border-purple-500 transition">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-600 to-pink-500 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Screen Repair</h3>
                    <p class="text-gray-400">LCD & touchscreen replacement with original quality parts</p>
                </div>

                <div class="glass-card p-8 rounded-2xl hover:border-purple-500 transition">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-600 to-pink-500 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Battery Service</h3>
                    <p class="text-gray-400">Battery replacement with warranty guarantee</p>
                </div>

                <div class="glass-card p-8 rounded-2xl hover:border-purple-500 transition">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-600 to-pink-500 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Hardware Repair</h3>
                    <p class="text-gray-400">Motherboard and component repair by experts</p>
                </div>

                <div class="glass-card p-8 rounded-2xl hover:border-purple-500 transition">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-600 to-pink-500 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Express Service</h3>
                    <p class="text-gray-400">Fast repair service completed in 1-2 hours</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimoni" class="py-20">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-5xl font-bold mb-4">
                    Customer <span class="gradient-text">Reviews</span>
                </h2>
                <p class="text-gray-400 text-xl">What our clients say about us</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <?php while($testimoni = mysqli_fetch_assoc($result_testimoni)): ?>
                <div class="glass-card p-8 rounded-2xl">
                    <div class="flex mb-4">
                        <?php for($i = 0; $i < $testimoni['rating']; $i++): ?>
                        <svg class="w-6 h-6 text-yellow-400 fill-current" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <?php endfor; ?>
                    </div>
                    <p class="text-gray-300 mb-6">"<?php echo htmlspecialchars($testimoni['komentar']); ?>"</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-600 to-pink-500 rounded-full flex items-center justify-center font-bold text-xl">
                            <?php echo strtoupper(substr($testimoni['nama'], 0, 1)); ?>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-bold"><?php echo htmlspecialchars($testimoni['nama']); ?></h4>
                            <p class="text-gray-400 text-sm"><?php echo date('M d, Y', strtotime($testimoni['tanggal'])); ?></p>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 glass-card">
        <div class="container mx-auto px-4 max-w-4xl">
            <div class="text-center mb-16">
                <h2 class="text-5xl font-bold mb-4">
                    Get In <span class="gradient-text">Touch</span>
                </h2>
                <p class="text-gray-400 text-xl">Ready to fix your device? Contact us now!</p>
            </div>

            <div class="glass-card p-8 md:p-12 rounded-2xl">
                <div class="grid md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <h3 class="text-2xl font-bold mb-6">Contact Info</h3>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-600 to-pink-500 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-sm">Phone</p>
                                    <p class="font-semibold">0889-5065-750</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-600 to-pink-500 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-sm">Email</p>
                                    <p class="font-semibold">info@techrestore.com</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-600 to-pink-500 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-sm">Address</p>
                                    <p class="font-semibold">Ds.Blungun, Kecamatan Jepon, Kabupaten Blora</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-2xl font-bold mb-6">Send Message</h3>
                        <form id="contactForm" class="space-y-4">
                            <input type="text" name="nama" placeholder="Your Name" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg focus:outline-none focus:border-purple-500" required>
                            <input type="email" name="email" placeholder="Your Email" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg focus:outline-none focus:border-purple-500" required>
                            <textarea name="pesan" rows="4" placeholder="Your Message" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg focus:outline-none focus:border-purple-500" required></textarea>
                            <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-pink-500 px-6 py-3 rounded-lg font-bold btn-glow">
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 border-t border-white/10">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center space-x-3 mb-4 md:mb-0">
                    <img src="logo.jpeg" alt="TechRestore Logo" class="w-10 h-10 object-contain rounded-full">
                    <span class="text-xl font-bold gradient-text">TechRestore</span>
                </div>
                <p class="text-gray-400">&copy; 2026 TechRestore_Himam Service. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Modal Detail Portfolio -->
    <div id="portfolioModal" class="modal">
        <div class="modal-content p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 id="modalTitle" class="text-3xl font-bold"></h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-white text-3xl">&times;</button>
            </div>
            <img id="modalImage" src="" alt="" class="w-full rounded-xl mb-6">
            <div class="grid md:grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-gray-400 text-sm">Category</p>
                    <p id="modalCategory" class="font-semibold"></p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Date</p>
                    <p id="modalDate" class="font-semibold"></p>
                </div>
            </div>
            <p id="modalDescription" class="text-gray-300 leading-relaxed"></p>
        </div>
    </div>

    <script>
        // Modal functions
        function openModal(id) {
            fetch('get_portfolio_detail.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modalTitle').textContent = data.judul;
                    document.getElementById('modalImage').src = 'uploads/' + data.gambar;
                    document.getElementById('modalCategory').textContent = data.kategori;
                    document.getElementById('modalDate').textContent = new Date(data.tanggal).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
                    document.getElementById('modalDescription').textContent = data.deskripsi;
                    document.getElementById('portfolioModal').classList.add('show');
                });
        }

        function closeModal() {
            document.getElementById('portfolioModal').classList.remove('show');
        }

        // Contact form
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('submit_contact.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if(data.success) {
                    this.reset();
                }
            });
        });

        // Close modal on outside click
        window.onclick = function(event) {
            const modal = document.getElementById('portfolioModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
<?php mysqli_close($conn); ?>