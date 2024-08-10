<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Kategori dan Buku</title>
    <link rel="stylesheet" href="styles/css/style.css">
</head>

<body>
    <!-- Menu Navigasi -->
    <nav class="navbar">
        <ul class="navbar-menu">
            <li><a href="index.php">Beranda</a></li>
            <li><a href="form.php">Form Kategori dan Buku</a></li>
            <li><a href="list.php">Daftar Buku dan Kategori</a></li>
            <li><a href="filter.php">Filter Buku</a></li>
        </ul>
    </nav>

    <!-- Form untuk Menambahkan atau Mengedit Kategori -->
    <form id="category-form" class="form-container" action="" method="post">
        <h3>Tambah/Edit Kategori</h3>
        <label for="category-name">Nama Kategori:</label>
        <input type="text" id="category-name" name="category-name" required>
        <button type="submit" name="save-category" class="btn">Simpan</button>
    </form>

    <!-- Form untuk Menambahkan atau Mengedit Buku -->
    <form id="book-form" class="form-container" action="" method="post">
        <h3>Tambah/Edit Buku</h3>
        <label for="book-title">Judul Buku:</label>
        <input type="text" id="book-title" name="book-title" required>

        <label for="book-author">Penulis:</label>
        <input type="text" id="book-author" name="book-author" required>

        <label for="book-publisher">Penerbit:</label>
        <input type="text" id="book-publisher" name="book-publisher" required>

        <label for="book-category">Kategori:</label>
        <select id="book-category" name="book-category" required>
            <!-- Option kategori akan diisi secara dinamis dari database -->
            <?php
            include 'db_config.php'; // Masukkan file konfigurasi database

            // Mengambil data kategori dari tabel categories
            $sql = "SELECT id, name FROM categories";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Menampilkan setiap kategori sebagai opsi di dropdown
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                }
            } else {
                echo '<option value="">Tidak ada kategori tersedia</option>';
            }

            $conn->close(); // Tutup koneksi database
            ?>
        </select>

        <label for="book-pages">Jumlah Halaman:</label>
        <input type="number" id="book-pages" name="book-pages" required>

        <label for="book-date">Tanggal Publikasi:</label>
        <input type="date" id="book-date" name="book-date" required>

        <button type="submit" name="save-book" class="btn">Simpan</button>
    </form>

    <?php
    include 'db_config.php';

    // Menangani form kategori
    if (isset($_POST['save-category'])) {
        $category_name = $_POST['category-name'];

        $sql = "INSERT INTO categories (name) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $category_name);

        if ($stmt->execute()) {
            echo "<p>Kategori berhasil disimpan.</p>";
        } else {
            echo "<p>Terjadi kesalahan: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }

    // Menangani form buku
    if (isset($_POST['save-book'])) {
        $title = $_POST['book-title'];
        $author = $_POST['book-author'];
        $publisher = $_POST['book-publisher'];
        $category_id = $_POST['book-category'];
        $pages = $_POST['book-pages'];
        $publication_date = $_POST['book-date'];

        // Query untuk menyimpan data buku
        $sql = "INSERT INTO books (title, author, publisher, category_id, pages, publication_date) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Binding parameter (pastikan tipe data sesuai)
        $stmt->bind_param("sssiis", $title, $author, $publisher, $category_id, $pages, $publication_date);

        // Eksekusi query dan cek hasilnya
        if ($stmt->execute()) {
            echo "<p>Buku berhasil disimpan.</p>";
        } else {
            echo "<p>Terjadi kesalahan: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }


    // Mengambil kategori untuk dropdown
    $sql = "SELECT id, name FROM categories";
    $result = $conn->query($sql);

    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }

    $conn->close();
    ?>

    <script>
        // JavaScript untuk mengisi dropdown kategori
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('book-category');
            <?php foreach ($categories as $category): ?>
                const option = document.createElement('option');
                option.value = "<?php echo $category['id']; ?>";
                option.textContent = "<?php echo $category['name']; ?>";
                categorySelect.appendChild(option);
            <?php endforeach; ?>
        });
    </script>
</body>

</html>