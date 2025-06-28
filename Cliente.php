<?php
session_start();
if (!isset($_SESSION['correo']) || $_SESSION['tipo'] !== 'cliente') {
    header('Location: vistas/login.php');
    exit;
}

require_once __DIR__ . '/config/Conexion.php';
$db   = new Conexion();
$conn = $db->iniciar();

// qué vista mostrar: home, authors, categories o filtros
$view = $_GET['view'] ?? 'home';
$limit = 50;
$books = [];
$title_heading = '';
$error = '';

try {
    // 1) Buscador por título
    if (!empty($_GET['search'])) {
        $q   = urlencode($_GET['search']);
        $url = "https://openlibrary.org/search.json?title={$q}&limit={$limit}";
        $raw = file_get_contents($url);
        $json = json_decode($raw, true);
        $books = $json['docs'] ?? [];
        $title_heading = "Resultados para “" . htmlspecialchars($_GET['search']) . "”";

    // 2) Filtrar por autor
    } elseif (!empty($_GET['author'])) {
        $a   = urlencode($_GET['author']);
        $url = "https://openlibrary.org/search.json?author={$a}&limit={$limit}";
        $raw = file_get_contents($url);
        $json = json_decode($raw, true);
        $books = $json['docs'] ?? [];
        $title_heading = "Libros de “" . htmlspecialchars($_GET['author']) . "”";

    // 3) Filtrar por categoría (subject)
    } elseif (!empty($_GET['subject'])) {
        $s   = urlencode($_GET['subject']);
        $url = "https://openlibrary.org/subjects/{$s}.json?limit={$limit}";
        $raw = file_get_contents($url);
        $json = json_decode($raw, true);
        $books = $json['works'] ?? [];
        $title_heading = "Categoría: “" . htmlspecialchars($_GET['subject']) . "”";

    // 4) Listar autores
    } elseif ($view === 'authors') {
        $url = "https://openlibrary.org/subjects/fiction.json?limit={$limit}";
        $raw = file_get_contents($url);
        $json = json_decode($raw, true);
        $list = $json['works'] ?? [];
        $auts = [];
        foreach ($list as $w) {
            if (!empty($w['authors'])) {
                foreach ($w['authors'] as $a) {
                    if (!empty($a['name'])) {
                        $auts[] = $a['name'];
                    }
                }
            }
        }
        $order = $_GET['order'] ?? 'asc';
        $authors = array_unique($auts);
        natcasesort($authors);
        if ($order === 'desc') {
            $authors = array_reverse($authors);
        }
        $title_heading = "Autores";

    // 5) Listar categorías
    } elseif ($view === 'categories') {
        $categories = [
            'fiction'         => 'Ficción',
            'science_fiction' => 'Ciencia ficción',
            'romance'         => 'Romance',
            'mystery'         => 'Misterio'
        ];
        $order = $_GET['order'] ?? 'asc';
        asort($categories); // Orden ascendente por defecto

        if ($order === 'desc') {
            $categories = array_reverse($categories);
        }
        $title_heading = "Categorías";

    // 6) Home por defecto: primeros 50 de ficción
    } else {
        $order = $_GET['order'] ?? 'asc';
        $url = "https://openlibrary.org/subjects/fiction.json?limit={$limit}";
        $raw = file_get_contents($url);
        $json = json_decode($raw, true);
        $books = $json['works'] ?? [];

        // Ordenar por título
        usort($books, function ($a, $b) use ($order) {
            $titleA = strtolower($a['title'] ?? '');
            $titleB = strtolower($b['title'] ?? '');
            return $order === 'desc' ? strcmp($titleB, $titleA) : strcmp($titleA, $titleB);
        });

        $title_heading = "Novedades en Ficción";
    }
} catch (Exception $e) {
    $error = "Error al conectar con la API: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Libros</title>
</head>
<body>
<nav class="navbar">
    <div class="navbar-container">
        <form method="get" action="cliente.php" class="search-form">
            <input type="text" name="search" placeholder="Buscar por título, autor o tema..."
                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <button type="submit">🔍</button>
        </form>

        <div class="navbar-links">
            <a href="cliente.php">🏠 Inicio</a>
            <a href="cliente.php?view=authors">👩‍💼 Autores</a>
            <a href="cliente.php?view=categories">📚 Categorías</a>
            <a href="orders.php">🛒 Mis Pedidos</a>
            <form method="post" action="vistas/logout.php" class="logout-form">
                <button type="submit">⏻ Cerrar sesión</button>
            </form>
        </div>
    </div>
</nav>

<style>
    body {
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
    }

    .navbar {
        background-color: #1f2d3d;
        padding: 0;
        width: 100%;
    }

    .navbar-container {
        max-width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 10px 20px;
    }

    .search-form {
        width: 100%;
        max-width: 700px;
        display: flex;
        margin-bottom: 12px;
    }

    .search-form input[type="text"] {
        flex: 1;
        padding: 10px 15px;
        border: 1px solid #ccc;
        border-radius: 6px 0 0 6px;
        font-size: 16px;
        background-color: #f0f4f8;
    }

    .search-form button {
        padding: 10px 20px;
        background-color: #3498db;
        color: white;
        border: none;
        border-radius: 0 6px 6px 0;
        font-size: 16px;
        cursor: pointer;
    }

    .search-form button:hover {
        background-color: #2980b9;
    }

    .navbar-links {
        display: flex;
        width: 100%;
        justify-content: space-around;
        flex-wrap: wrap;
        background-color: #2980b9;
        padding: 12px 0;
    }

    .navbar-links a {
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        font-weight: 500;
        font-size: 16px;
        background-color: #3498db;
        margin: 5px;
        border-radius: 8px;
        transition: background-color 0.3s;
        flex-grow: 1;
        text-align: center;
    }

    .navbar-links a:hover {
        background-color: #1d7eb8;
    }

    .logout-form {
        margin: 5px;
        flex-grow: 1;
        text-align: center;
    }

    .logout-form button {
        background-color: #e74c3c;
        color: white;
        padding: 10px 20px;
        font-size: 16px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        width: 100%;
        font-weight: bold;
        transition: background-color 0.3s;
    }

    .logout-form button:hover {
        background-color: #c0392b;
    }

    @media (max-width: 600px) {
        .navbar-links a,
        .logout-form button {
            font-size: 14px;
            padding: 10px 10px;
        }
    }
    .orden-container {
    text-align: center;
    margin: 30px 0 10px;
    }

    .orden-label {
        font-weight: 500;
        font-size: 16px;
        margin-right: 10px;
        color: #2c3e50;
    }

    .orden-select {
        padding: 8px 14px;
        font-size: 15px;
        border: 1px solid #ccc;
        border-radius: 6px;
        background-color: #f0f4f8;
        cursor: pointer;
        transition: border-color 0.3s, background-color 0.3s;
    }

    .orden-select:hover {
        border-color: #2980b9;
        background-color: #e6f0fa;
    }
</style>



<h1><?= htmlspecialchars($title_heading) ?></h1>

<?php if ($view === 'authors'): ?>
    <?php
        // Ordenar autores según la opción seleccionada (por defecto A-Z)
        $order = $_GET['order'] ?? 'asc';
        $authors = array_unique($auts);
        natcasesort($authors);
        $authors = $order === 'desc' ? array_reverse($authors) : $authors;
    ?>

    <div class="orden-container">
        <form method="get" action="cliente.php">
            <input type="hidden" name="view" value="authors">
            <label for="order" class="orden-label">Ordenar por nombre:</label>
            <select name="order" id="order" class="orden-select" onchange="this.form.submit()">
                <option value="asc" <?= $order === 'asc' ? 'selected' : '' ?>>Ascendente (A-Z)</option>
                <option value="desc" <?= $order === 'desc' ? 'selected' : '' ?>>Descendente (Z-A)</option>
            </select>
        </form>
    </div>
    
    <style>
        .author-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            margin-top: 20px;
            justify-content: center;
        }

        .author-card {
            background-color: #f0f8ff;
            border: 1px solid #d0e9f7;
            border-radius: 8px;
            padding: 12px 18px;
            min-width: 180px;
            text-align: center;
            font-weight: 500;
            color: #0077b6;
            box-shadow: 0 2px 5px rgba(0,0,0,0.08);
            transition: transform 0.2s, background-color 0.3s;
        }

        .author-card:hover {
            transform: scale(1.03);
            background-color: #e0f4ff;
        }

        .author-card a {
            text-decoration: none;
            color: inherit;
        }
        
    </style>

    <div class="author-grid">
    <?php foreach ($authors as $a): ?>
        <div class="author-card">
            <a href="cliente.php?author=<?= urlencode($a) ?>">
                <?= htmlspecialchars($a) ?>
            </a>
        </div>
    <?php endforeach; ?>
    </div>

<?php elseif ($view === 'categories'): ?>
    <style>
        .category-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            margin-top: 20px;
            justify-content: center;
        }

        .category-card {
            background-color: #e6f4fa;
            border: 1px solid #c0e2f2;
            border-radius: 8px;
            padding: 16px 20px;
            min-width: 180px;
            text-align: center;
            font-weight: 500;
            color: #0077b6;
            box-shadow: 0 2px 5px rgba(0,0,0,0.08);
            transition: transform 0.2s, background-color 0.3s;
        }

        .category-card:hover {
            transform: scale(1.05);
            background-color: #d4f1ff;
        }

        .category-card a {
            text-decoration: none;
            color: inherit;
            font-size: 16px;
        }
    </style>

    <div class="orden-container">
        <form method="get" action="cliente.php">
            <input type="hidden" name="view" value="categories">
            <label for="order" class="orden-label">Ordenar por nombre:</label>
            <select name="order" id="order" class="orden-select" onchange="this.form.submit()">
                <option value="asc" <?= $order === 'asc' ? 'selected' : '' ?>>Ascendente (A-Z)</option>
                <option value="desc" <?= $order === 'desc' ? 'selected' : '' ?>>Descendente (Z-A)</option>
            </select>
        </form>
    </div>
    
  

    <div class="category-grid">
    <?php foreach ($categories as $key => $label): ?>
        <div class="category-card">
            <a href="cliente.php?subject=<?= htmlspecialchars($key) ?>">
                <?= htmlspecialchars($label) ?>
            </a>
        </div>
    <?php endforeach; ?>
    </div>


<?php else: ?>
    <?php if (!empty($error)): ?>
        <p style="color:red"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <style>
        
    .book-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        margin-top: 20px;
    }

    .book-card {
        width: 200px;
        background-color: #ffffff;
        border: 1px solid #d1e9f9;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.06);
        padding: 16px;
        text-align: center;
        font-family: 'Segoe UI', sans-serif;
        transition: transform 0.2s;
    }

    .book-card:hover {
        transform: translateY(-5px);
    }

    .book-card img {
        height: 150px;
        object-fit: contain;
        margin-bottom: 10px;
    }

    .book-card h4 {
        font-size: 16px;
        margin: 10px 0 5px;
        color: #0077b6;
    }

    .book-card p {
        margin: 4px 0;
        font-size: 14px;
        color: #333;
    }

    .book-card button {
        background-color: #00b4d8;
        border: none;
        color: white;
        padding: 8px 14px;
        border-radius: 6px;
        cursor: pointer;
        margin-top: 10px;
        transition: background-color 0.3s;
    }

    .book-card button:hover {
        background-color: #0096c7;
    }
</style>
<div class="orden-container">
    <form method="get" action="cliente.php">
        <input type="hidden" name="order" value="<?= htmlspecialchars($order) ?>">
        <label for="order" class="orden-label">Ordenar por título:</label>
        <select name="order" id="order" class="orden-select" onchange="this.form.submit()">
            <option value="asc" <?= $order === 'asc' ? 'selected' : '' ?>>Ascendente (A-Z)</option>
            <option value="desc" <?= $order === 'desc' ? 'selected' : '' ?>>Descendente (Z-A)</option>
        </select>
    </form>
</div>
<div class="book-grid">
<?php foreach ($books as $b):
    $title = $b['title'] ?? ($b['title_suggest'] ?? 'Sin título');
    $authors = [];
    if (!empty($b['authors'])) {
        foreach ($b['authors'] as $a) {
            if (!empty($a['name'])) {
                $authors[] = $a['name'];
            }
        }
    }
    if (!empty($b['author_name'])) {
        $authors = $b['author_name'];
    }
    $author_list = implode(", ", $authors);

    if (!empty($b['cover_id'])) {
        $img = "https://covers.openlibrary.org/b/id/{$b['cover_id']}-M.jpg";
    } elseif (!empty($b['cover_i'])) {
        $img = "https://covers.openlibrary.org/b/id/{$b['cover_i']}-M.jpg";
    } else {
        $img = 'https://via.placeholder.com/150x200?text=Sin+portada';
    }

    $price = 20.00;
?>
    <div class="book-card">
        <img src="<?= htmlspecialchars($img) ?>" alt="Portada">
        <h4><?= htmlspecialchars($title) ?></h4>
        <p><i><?= htmlspecialchars($author_list) ?></i></p>
        <p>US$ <?= number_format($price, 2) ?></p>
        <form method="post" action="pedido.php">
            <input type="hidden" name="title" value="<?= htmlspecialchars($title) ?>">
            <input type="hidden" name="price" value="<?= htmlspecialchars($price) ?>">
            <button type="submit">Pedir</button>
        </form>
    </div>
<?php endforeach; ?>
</div>

<?php endif; ?>

</body>
</html>
