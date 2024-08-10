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
            li.textContent = category.name;
            categoriesUl.appendChild(li);
          });
        })
        .catch(error => console.error('Error fetching categories:', error));
    }

    // Muat data saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
      loadBooks();
      loadCategories();
    });
  </script>
</body>

</html>