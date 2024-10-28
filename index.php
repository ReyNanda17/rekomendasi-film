<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Menarik</title>
    <!-- Tambahkan Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
$data = include 'dataset.php';

// Fungsi quickSort untuk mengurutkan data
function quickSort($data, $key) {
    // Jika data kosong atau hanya 1 elemen, langsung kembalikan
    if (count($data) <= 1) return $data;

    $left = $right = [];
    $pivot = array_shift($data);  // Elemen pivot

    foreach ($data as $item) {
        if ($item[$key] >= $pivot[$key]) {
            $left[] = $item;
        } else {
            $right[] = $item;
        }
    }

    return array_merge(quickSort($left, $key), [$pivot], quickSort($right, $key));
}

// Fungsi untuk memfilter berdasarkan genre
function filterByGenre($data, $genre) {
    if (empty($genre)) return $data; // Jika genre tidak dipilih, kembalikan semua data

    // Filter data berdasarkan genre yang dipilih
    return array_filter($data, function ($item) use ($genre) {
        return $item['genre'] === $genre;
    });
}

// Ambil parameter GET untuk genre dan sorting
$selectedGenre = isset($_GET['genre']) ? $_GET['genre'] : '';
$kriteria = isset($_GET['sort']) ? $_GET['sort'] : 'rating';

// Filter data berdasarkan genre
$filteredData = filterByGenre($data, $selectedGenre);

// Jika hasil filter kosong, langsung kembalikan array kosong agar aman
if (empty($filteredData)) {
    $sortedData = [];
} else {
    // Jalankan quickSort jika data tidak kosong
    $sortedData = quickSort($filteredData, $kriteria);
}

// Pagination (membagi data per halaman)
$itemsPerPage = 5;
$totalItems = count($sortedData);
$totalPages = ceil($totalItems / $itemsPerPage);
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$startIndex = ($currentPage - 1) * $itemsPerPage;
$pagedData = array_slice($sortedData, $startIndex, $itemsPerPage);

?>
<?php include 'header.php'; ?>

<div class="container">
    <h2>Daftar Film</h2>

    <div class="form-group">
        <form method="GET" action="">
            <label for="genre">Pilih Genre:</label>
            <select name="genre" id="genre">
                <option value="">Semua Genre</option>
                <option value="Action" <?php if ($selectedGenre == 'Action') echo 'selected'; ?>>Action</option>
                <option value="Drama" <?php if ($selectedGenre == 'Drama') echo 'selected'; ?>>Drama</option>
                <option value="Horror" <?php if ($selectedGenre == 'Horror') echo 'selected'; ?>>Horror</option>
                <option value="Sci-Fi" <?php if ($selectedGenre == 'Sci-Fi') echo 'selected'; ?>>Sci-Fi</option>
                <option value="Animation" <?php if ($selectedGenre == 'Animation') echo 'selected'; ?>>Animation</option>
            </select>
            <button type="submit">Tampilkan</button>
        </form>

        <form method="GET" action="">
            <label for="sort">Urutkan Berdasarkan:</label>
            <select name="sort" id="sort">
                <option value="rating" <?php if ($kriteria == 'rating') echo 'selected'; ?>>Rating</option>
                <option value="popularitas" <?php if ($kriteria == 'popularitas') echo 'selected'; ?>>Popularitas</option>
                <option value="tahun_rilis" <?php if ($kriteria == 'tahun_rilis') echo 'selected'; ?>>Tahun Rilis</option>
            </select>
            <button type="submit">Urutkan</button>
        </form>
    </div>
    <table class="table table-striped table-responsive">
    <thead>
        <tr>
            <th>Poster</th>
            <th>Judul</th>
            <th>Rating</th>
            <th>Popularitas</th>
            <th>Tahun Rilis</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($pagedData)): ?>
            <?php foreach ($pagedData as $item): ?>
                <tr>
                    <td>
                        <img src="<?php echo htmlspecialchars($item['poster']); ?>" 
                             alt="Poster <?php echo htmlspecialchars($item['judul']); ?>" 
                             style="width: 100px; height: auto;">
                    </td>
                    <td><?php echo htmlspecialchars($item['judul']); ?></td>
                    <td><?php echo htmlspecialchars($item['rating']); ?></td>
                    <td><?php echo htmlspecialchars($item['popularitas']); ?></td>
                    <td><?php echo htmlspecialchars($item['tahun_rilis']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5">Tidak ada data untuk ditampilkan.</td></tr>
        <?php endif; ?>
    </tbody>
</table>


    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?php echo $i; ?>&sort=<?php echo $kriteria; ?>&genre=<?php echo $selectedGenre; ?>" class="<?php if ($i == $currentPage) echo 'active'; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
<!-- Scroll to Top Button -->
<button onclick="scrollToTop()" id="scrollBtn" class="btn btn-secondary" style="display: none; position: fixed; bottom: 20px; right: 20px;">⬆️</button>

<script>
    window.onscroll = function() {toggleScrollBtn()};
    
    function toggleScrollBtn() {
        document.getElementById("scrollBtn").style.display = (document.documentElement.scrollTop > 200) ? "block" : "none";
    }

    function scrollToTop() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
</script>


<!-- Tambahkan Bootstrap JS dan jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
