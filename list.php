<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Beranda</title>
    <link rel="stylesheet" href="styles/css/style.css" />
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

    <!-- Halaman Daftar Buku -->
    <div id="books-list" class="list-container">
        <h2>Daftar Buku</h2>
        <table id="books-table" class="table">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Penerbit</th>
                    <th>Kategori</th>
                    <th>Jumlah Halaman</th>
                    <th>Tanggal Publikasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Daftar buku akan diisi secara dinamis -->
            </tbody>
        </table>
    </div>

    <!-- Halaman Daftar Kategori -->
    <div id="categories-list" class="list-container">
        <h2>Daftar Kategori</h2>
        <ul id="categories-ul" class="category-list">
            <!-- Daftar kategori akan diisi secara dinamis -->
        </ul>
    </div>

    <script>
        // Fungsi untuk mengisi tabel buku
        function loadBooks() {
            fetch('get_books.php')
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.querySelector('#books-table tbody');
                    tableBody.innerHTML = ''; // Kosongkan tabel sebelum diisi

                    data.forEach(book => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${book.title}</td>
                            <td>${book.author}</td>
                            <td>${book.publisher}</td>
                            <td>${book.category}</td>
                            <td>${book.pages}</td>
                            <td>${book.publication_date}</td>
                            <td>
                                <button class="edit-btn" onclick="editBook(${book.id})">Edit</button>
                                <button class="delete-btn" onclick="deleteBook(${book.id})">Hapus</button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                })
                .catch(error => console.error('Error fetching books:', error));
        }

        // Fungsi untuk mengisi daftar kategori
        function loadCategories() {
            fetch('get_categories.php')
                .then(response => response.json())
                .then(data => {
                    const categoriesUl = document.querySelector('#categories-ul');
                    categoriesUl.innerHTML = ''; // Kosongkan daftar sebelum diisi

                    data.forEach(category => {
                        const li = document.createElement('li');
                        li.innerHTML = `
                            ${category.name}
                            <button class="edit-btn" onclick="editCategory(${category.id})">Edit</button>
                            <button class="delete-btn" onclick="deleteCategory(${category.id})">Hapus</button>
                        `;
                        categoriesUl.appendChild(li);
                    });
                })
                .catch(error => console.error('Error fetching categories:', error));
        }

        // Fungsi untuk mengedit buku
        function editBook(bookId) {
            // Redirect ke halaman formulir dengan ID buku yang akan diedit
            window.location.href = `form.php?book_id=${bookId}`;
        }

        // Fungsi untuk menghapus buku
        function deleteBook(bookId) {
            if (confirm('Apakah Anda yakin ingin menghapus buku ini?')) {
                fetch(`delete_book.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id=${bookId}`
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert('Buku berhasil dihapus.');
                        loadBooks(); // Reload daftar buku
                    } else {
                        alert('Gagal menghapus buku.');
                    }
                })
                .catch(error => console.error('Error deleting book:', error));
            }
        }

        // Fungsi untuk mengedit kategori
        function editCategory(categoryId) {
            // Redirect ke halaman formulir dengan ID kategori yang akan diedit
            window.location.href = `form.php?category_id=${categoryId}`;
        }

        // Fungsi untuk menghapus kategori
        function deleteCategory(categoryId) {
            if (confirm('Apakah Anda yakin ingin menghapus kategori ini?')) {
                fetch(`delete_category.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id=${categoryId}`
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert('Kategori berhasil dihapus.');
                        loadCategories(); // Reload daftar kategori
                    } else {
                        alert('Gagal menghapus kategori.');
                    }
                })
                .catch(error => console.error('Error deleting category:', error));
            }
        }

        // Muat data saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            loadBooks();
            loadCategories();
        });
    </script>
</body>
</html>
