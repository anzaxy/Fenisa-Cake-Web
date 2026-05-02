<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fenisa_cake";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8");

$cake_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$cake_data = null;

if ($cake_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM cake WHERE id = ?");
    $stmt->bind_param("i", $cake_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $cake_data = $result->fetch_assoc();
    }
    $stmt->close();
}

$order_success = false;
$error_message = "";
$id_order = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST['nama']);
    $kontak = trim($_POST['kontak']);
    $alamat = trim($_POST['alamat']);
    $cake_id_post = intval($_POST['cake_id']);
    
    if (empty($nama) || empty($kontak) || empty($alamat)) {
        $error_message = "Semua field harus diisi!";
    } elseif (strlen($nama) > 20) {
        $error_message = "Nama tidak boleh lebih dari 20 karakter!";
    } elseif (!is_numeric($kontak) || strlen($kontak) < 10 || strlen($kontak) > 13) {
        $error_message = "Nomor kontak harus berupa angka 10-13 digit!";
    } else {
        $stmt = $conn->prepare("SELECT nama_cake, harga FROM cake WHERE id = ?");
        $stmt->bind_param("i", $cake_id_post);
        $stmt->execute();
        $cake_result = $stmt->get_result();
        
        if ($cake_result->num_rows > 0) {
            $cake_order = $cake_result->fetch_assoc();
            
           
            $id_order = 'ORD' . date('YmdHis') . substr(microtime(), 2, 6) . sprintf('%03d', rand(100, 999));
            $is_unique = false;
            $attempts = 0;
            while (!$is_unique && $attempts < 5) {
                $check_stmt = $conn->prepare("SELECT id_order FROM `order` WHERE id_order = ?");
                $check_stmt->bind_param("s", $id_order);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();
                if ($check_result->num_rows == 0) {
                    $is_unique = true;
                } else {
                    usleep(1000); 
                    $id_order = 'ORD' . date('YmdHis') . substr(microtime(), 2, 6) . sprintf('%03d', rand(100, 999));
                    $attempts++;
                }
                $check_stmt->close();
            }
            
            if ($is_unique) {
                $stmt_order = $conn->prepare("INSERT INTO `order` (id_order, nama_cake, harga, nama, kontak, alamat) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt_order->bind_param("ssdsss", 
                    $id_order,
                    $cake_order['nama_cake'], 
                    $cake_order['harga'], 
                    $nama, 
                    $kontak, 
                    $alamat
                );
                if ($stmt_order->execute()) {
                    $order_success = true;
                    $stmt_update = $conn->prepare("UPDATE cake SET stok = stok - 1 WHERE id = ? AND stok > 0");
                    $stmt_update->bind_param("i", $cake_id_post);
                    $stmt_update->execute();
                    $stmt_update->close();
                } else {
                    $error_message = "Gagal menyimpan pesanan. Silakan coba lagi. Error: " . $conn->error;
                }
                $stmt_order->close();
            } else {
                $error_message = "Gagal generate ID unik. Silakan coba lagi.";
            }
        } else {
            $error_message = "Cake tidak ditemukan!";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Cake - Fenisa Dreams Bakery</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body style="background:rgb(244, 227, 255);">
    <header>
        <div class="container">
            <div class="header-content">
                <h1>𝓕𝓮𝓷𝓲𝓼𝓪 𝓓𝓻𝓮𝓪𝓶𝓼 𝓑𝓪𝓴𝓮𝓻𝔂</h1>
            </div>
        </div>
    </header>

    <main>
        <section class="search-results">
            <div class="container">
                <?php if ($order_success): ?>
                    <div class="order-success">
                        <h2>✅ Pesanan Berhasil!</h2>
                        <div class="success-message">
                            <p><strong>Terima kasih atas pesanan Anda!</strong></p>
                            <p>Pesanan Anda telah berhasil disimpan dan akan segera diproses.</p>
                            <p>Tim kami akan menghubungi Anda dalam 1x24 jam untuk konfirmasi lebih lanjut.</p>
                            <p><strong>ID Pesanan: <?php echo htmlspecialchars($id_order); ?></strong></p>
                        </div>
                        <div class="order-actions">
                            <a href="index.html" class="btn-back">Kembali ke Beranda</a>
                            <a href="cake.php" class="btn-show-all">Lihat Menu Lainnya</a>
                        </div>
                    </div>
                <?php elseif ($cake_data): ?>
                    <h2>Pesan Cake</h2>
                    
                    <div class="cake-detail">
                        <div class="cake-info">
                            <h3><?php echo htmlspecialchars($cake_data['nama_cake']); ?></h3>
                            <p class="cake-description"><?php echo htmlspecialchars($cake_data['deskripsi']); ?></p>
                            <div class="cake-meta">
                                <span class="price">Rp <?php echo number_format($cake_data['harga'], 0, ',', '.'); ?></span>
                                <span class="category-badge"><?php echo htmlspecialchars($cake_data['kategori']); ?></span>
                                <div class="rating">
                                    <?php 
                                    $rating = $cake_data['rating'];
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo ($i <= $rating) ? "⭐" : "☆";
                                    }
                                    ?>
                                    <span class="rating-number">(<?php echo $rating; ?>/5)</span>
                                </div>
                                <p class="stock-info">
                                    <?php if ($cake_data['stok'] > 0): ?>
                                        <span class="stock-available">Stok: <?php echo $cake_data['stok']; ?> tersedia</span>
                                    <?php else: ?>
                                        <span class="stock-empty">Maaf, stok habis</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <?php if ($cake_data['stok'] > 0): ?>
                        <form id="orderForm" method="POST" action="">
                            <input type="hidden" name="cake_id" value="<?php echo $cake_data['id']; ?>">
                            
                            <?php if (!empty($error_message)): ?>
                                <div class="error-alert">
                                    <p><?php echo htmlspecialchars($error_message); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <div class="form-group">
                                <label for="nama">Nama Lengkap:</label>
                                <input type="text" id="nama" name="nama" required maxlength="20"
                                       placeholder="Masukkan nama lengkap (max 20 karakter)" 
                                       value="<?php echo isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="kontak">Nomor Kontak:</label>
                                <input type="tel" id="kontak" name="kontak" required maxlength="13"
                                       placeholder="Contoh: 08123456789" 
                                       value="<?php echo isset($_POST['kontak']) ? htmlspecialchars($_POST['kontak']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="alamat">Alamat Lengkap:</label>
                                <input type="text" id="alamat" name="alamat" required rows="4" 
                                          placeholder="Masukkan alamat lengkap untuk pengiriman"><?php echo isset($_POST['alamat']) ? htmlspecialchars($_POST['alamat']) : ''; ?></input>
                            </div>
                            
                            <div class="order-summary">
                                <h4>Ringkasan Pesanan:</h4>
                                <p><strong>Cake:</strong> <?php echo htmlspecialchars($cake_data['nama_cake']); ?></p>
                                <p><strong>Harga:</strong> <span class="price">Rp <?php echo number_format($cake_data['harga'], 0, ',', '.'); ?></span></p>
                                <small>*Harga belum termasuk ongkos kirim</small>
                            </div>
                            <button type="submit" class="search-btn" style="margin-bottom: 1rem;">Pesan Sekarang</button>
                        </form>
                    <?php else: ?>
                        <div class="stock-warning">
                            <p><strong>Maaf, cake ini sedang habis stok.</strong></p>
                            <p>Silakan pilih cake lainnya atau hubungi kami untuk pre-order.</p>
                        </div>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <div class="no-cake">
                        <h2>Cake Tidak Ditemukan</h2>
                        <p>Maaf, cake yang Anda cari tidak tersedia.</p>
                        <a href="index.html" class="btn-back">Kembali ke Beranda</a>
                    </div>
                <?php endif; ?>
                
                <?php if (!$order_success && $cake_data): ?>
                    <a href="cake.php" class="btn-back">Lihat Menu Lainnya</a>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 Anisa Zahra Salsabila & Feni Destiana</p>
        </div>
    </footer>
</body>
</html>