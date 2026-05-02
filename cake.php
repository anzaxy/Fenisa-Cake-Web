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

$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';

$sql = "SELECT * FROM cake WHERE 1=1";
$params = array();
$types = "";

if (!empty($keyword)) {
    $sql .= " AND (nama_cake LIKE ? OR deskripsi LIKE ?)";
    $params[] = "%" . $keyword . "%";
    $params[] = "%" . $keyword . "%";
    $types .= "ss";
}

if (!empty($kategori)) {
    $sql .= " AND kategori = ?";
    $params[] = $kategori;
    $types .= "s";
}

$sql .= " ORDER BY id ASC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian - Fenisa Dreams Bakery</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body style="background:rgb(244, 227, 255);">
    <header>
        <div class="container">
            <div class="header-content">
                <h1>𝓕𝓮𝓷𝓲𝓼𝓪 𝓓𝓻𝓮𝓪𝓶𝓼 𝓑𝓪𝓴𝓮𝓻𝔂</h1>
                

                <div class="header-search">
                    <form id="searchForm" action="cake.php" method="GET" class="search-form">
                        <input type="text" id="keyword" name="keyword" placeholder="Cari cake..." class="search-input" 
                               value="<?php echo htmlspecialchars($keyword); ?>">
                        <select id="kategori" name="kategori" class="search-select">
                            <option value="">Semua Kategori</option>
                            <option value="Birthday" <?php echo ($kategori == 'Birthday') ? 'selected' : ''; ?>>Birthday Cake</option>
                            <option value="Wedding" <?php echo ($kategori == 'Wedding') ? 'selected' : ''; ?>>Wedding Cake</option>
                            <option value="Anniversary" <?php echo ($kategori == 'Anniversary') ? 'selected' : ''; ?>>Anniversary Cake</option>
                            <option value="Custom" <?php echo ($kategori == 'Custom') ? 'selected' : ''; ?>>Custom Cake</option>
                            <option value="Cupcake" <?php echo ($kategori == 'Cupcake') ? 'selected' : ''; ?>>Cupcake</option>
                        </select>
                        <button type="submit" id="searchBtn" class="search-btn">Cari</button>
                        <span class="error-message" id="errorMessage"></span>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main>
        <section class="search-results">
            <div class="container">
                <h2 >Hasil Pencarian</h2>
                
                <?php if (!empty($keyword) || !empty($kategori)): ?>
                    <div class="search-info">
                        <p><strong>Kriteria Pencarian:</strong></p>
                        <?php if (!empty($keyword)): ?>
                            <p>Kata Kunci: "<em><?php echo htmlspecialchars($keyword); ?></em>"</p>
                        <?php endif; ?>
                        <?php if (!empty($kategori)): ?>
                            <p>Kategori: <em><?php echo htmlspecialchars($kategori); ?></em></p>
                        <?php endif; ?>
                        <hr>
                    </div>
                <?php endif; ?>

                <div class="table-container">
                    <?php if ($result->num_rows > 0): ?>
                        <p class="result-count">
                            <strong>Ditemukan <?php echo $result->num_rows; ?> cake</strong>
                        </p>
                        
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Cake</th>
                                    <th>Deskripsi</th>
                                    <th>Harga</th>
                                    <th>Kategori</th>
                                    <th>Stok</th>
                                    <th>Rating</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row["id"]; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($row["nama_cake"]); ?></strong>
                                    </td>
                                    <td><?php echo htmlspecialchars($row["deskripsi"]); ?></td>
                                    <td class="price">
                                        Rp <?php echo number_format($row["harga"], 0, ',', '.'); ?>
                                    </td> 
                                    <td>
                                        <span class="category-badge category-<?php echo strtolower($row["kategori"]); ?>">
                                            <?php echo htmlspecialchars($row["kategori"]); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($row["stok"] > 0): ?>
                                            <span class="stock-available"><?php echo $row["stok"]; ?> tersedia</span>
                                        <?php else: ?>
                                            <span class="stock-empty">Habis</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="rating">
                                            <?php 
                                            $rating = $row["rating"];
                                            for ($i = 1; $i <= 5; $i++) {
                                                if ($i <= $rating) {
                                                    echo "⭐";
                                                } else {
                                                    echo "☆";
                                                }
                                            }
                                            ?>
                                            <span class="rating-number">(<?php echo $rating; ?>/5)</span>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($row["stok"] > 0): ?>
                                            <a href="order.php?id=<?php echo $row['id']; ?>" class="btn-order">Pesan</a>
                                        <?php else: ?>
                                            <span class="btn-disabled">Habis</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="no-results">
                            <h3>Tidak Ada Hasil Ditemukan</h3>
                            <p>Maaf, tidak ada cake yang sesuai dengan kriteria pencarian Anda.</p>
                            <div class="suggestions">
                                <h4>Saran:</h4>
                                <ul>
                                    <li>Periksa ejaan kata kunci</li>
                                    <li>Gunakan kata kunci yang lebih umum</li>
                                    <li>Coba kategori yang berbeda</li>
                                    <li>Kosongkan filter untuk melihat semua cake</li>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="back-to-search">
                    <a href="index.html" class="btn-back">Beranda</a>
                    <a href="?keyword=&kategori=" class="btn-show-all">Tampilkan Semua Cake</a>
                </div>
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

<?php
$conn->close();
?>