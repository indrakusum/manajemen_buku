<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter Buku</title>
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

    <!-- Filter Buku -->
    <div id="filter-section" class="filter-container">
        <h3>Filter Buku</h3>
        <input type="text" id="filter-text" class="filter-input" placeholder="Cari Judul, Penulis, Penerbit">
        <select id="filter-category" class="filter-input">
            <option value="">Semua Kategori</option>
            <!-- Opsi kategori akan diisi secara dinamis -->
        </select>
        <input type="date" id="filter-date-start" class="filter-input" placeholder="Tanggal Mulai">
        <input type="date" id="filter-date-end" class="filter-input" placeholder="Tanggal Akhir">
        <button onclick="applyFilters()" class="btn">Terapkan Filter</button>
    </div>

    <!-- Hasil Filter -->
    <div id="filtered-books" class="list-container">
        <h2>Hasil Filter Buku</h2>
        <table id="books-table" class="table">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Penerbit</th>
                    <th>Kategori</th>
                    <th>Tanggal Publikasi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Hasil filter buku akan diisi secara dinamis -->
            </tbody>
        </table>
    </div>

    <script>
        // Muat kategori ke dropdown filter
        function loadCategories() {
            fetch('get_categories.php')
                .then(response => response.json())
                .then(data => {
                    const categorySelect = document.getElementById('filter-category');
                    data.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category.id;
                        option.textContent = category.name;
                        categorySelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching categories:', error));
        }

        // Terapkan filter berdasarkan input pengguna
        function applyFilters() {
            const filterText = document.getElementById('filter-text').value.toLowerCase();
            const filterCategory = document.getElementById('filter-category').value;
            const filterDateStart = document.getElementById('filter-date-start').value;
            const filterDateEnd = document.getElementById('filter-date-end').value;

            fetch('get_books.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `text=${filterText}&category=${filterCategory}&date_start=${filterDateStart}&date_end=${filterDateEnd}`
            })
            .then(response => response.json())
            .then(data => {
                const tableBody = document.querySelector('#books-table tbody');
                tableBody.innerHTML = ''; // Kosongkan tabel sebelum diisi

                if (data.length > 0) {
                    data.forEach(book => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${book.title}</td>
                            <td>${book.author}</td>
                            <td>${book.publisher}</td>
                            <td>${book.category}</td>
                            <td>${book.publication_date}</td>
                        `;
                        tableBody.appendChild(row);
                    });
                } else {
                    const row = document.createElement('tr');
                    row.innerHTML = '<td colspan="5" style="text-align:center;">Tidak ada buku yang ditemukan</td>';
                    tableBody.appendChild(row);
                }
            })
            .catch(error => console.error('Error fetching filtered books:', error));
        }

        // Muat kategori saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            loadCategories();
        });
    </script>
</body>
</html>
