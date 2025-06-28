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
        $authors = array_unique($auts);
        $title_heading = "Autores";

    // 5) Listar categorías
    } elseif ($view === 'categories') {
        $categories = [
            'fiction'         => 'Ficción',
            'science_fiction' => 'Ciencia ficción',
            'romance'         => 'Romance',
            'mystery'         => 'Misterio'
        ];
        $title_heading = "Categorías";

    // 6) Home por defecto: primeros 50 de ficción
    } else {
        $url = "https://openlibrary.org/subjects/fiction.json?limit={$limit}";
        $raw = file_get_contents($url);
        $json = json_decode($raw, true);
        $books = $json['works'] ?? [];
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
    <div class="nav-left">
        <form method="get" action="cliente.php" class="search-form">
            <input type="text" name="search" placeholder="Buscar libro..."
                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <button type="submit">🔍</button>
        </form>
    </div>
    <div class="nav-right">
        <a href="cliente.php">Inicio</a>
        <a href="cliente.php?view=authors">Autores</a>
        <a href="cliente.php?view=categories">Categorías</a>
        <a href="orders.php">Mis Pedidos</a>
        <a href="vistas/logout.php" class="logout">Cerrar sesión</a>
    </div>
</nav>

<style>
    .navbar {
        background-color: #2c3e50;
        padding: 12px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-family: 'Segoe UI', sans-serif;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .search-form {
        display: flex;
        align-items: center;
    }

    .search-form input[type="text"] {
        padding: 6px 10px;
        border: none;
        border-radius: 4px 0 0 4px;
        outline: none;
        font-size: 14px;
    }

    .search-form button {
        padding: 6px 10px;
        border: none;
        border-radius: 0 4px 4px 0;
        background-color: #3498db;
        color: white;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s;
    }

    .search-form button:hover {
        background-color: #2980b9;
    }

    .nav-right a {
        color: white;
        margin-left: 20px;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s;
    }

    .nav-right a:hover {
        color: #f1c40f;
    }

    .nav-right .logout {
        color: #e74c3c;
        font-weight: bold;
    }

    .nav-right .logout:hover {
        color: #c0392b;
    }
</style>


<h1><?= htmlspecialchars($title_heading) ?></h1>

<?php if ($view === 'authors'): ?>
    <ul>
    <?php foreach ($authors as $a): ?>
        <li>
          <a href="cliente.php?author=<?= urlencode($a) ?>">
            <?= htmlspecialchars($a) ?>
          </a>
        </li>
    <?php endforeach; ?>
    </ul>

<?php elseif ($view === 'categories'): ?>
    <ul>
    <?php foreach ($categories as $key => $label): ?>
        <li>
          <a href="cliente.php?subject=<?= htmlspecialchars($key) ?>">
            <?= htmlspecialchars($label) ?>
          </a>
        </li>
    <?php endforeach; ?>
    </ul>

<?php else: ?>
    <?php if (!empty($error)): ?>
        <p style="color:red"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <div style="display:flex;flex-wrap:wrap">
    <?php foreach ($books as $b):
        // unificar campos entre search.json y works.json
        $title = $b['title'] ?? ($b['title_suggest'] ?? 'Sin título');
        // autores
        $authors = [];
        if (!empty($b['authors'])) {
            // works.json usa ['authors'][*]['name']
            foreach ($b['authors'] as $a) {
                if (!empty($a['name'])) {
                    $authors[] = $a['name'];
                }
            }
        }
        if (!empty($b['author_name'])) {
            // search.json usa ['author_name']
            $authors = $b['author_name'];
        }
        $author_list = implode(", ", $authors);

        // portada
        if (!empty($b['cover_id'])) {
            $img = "https://covers.openlibrary.org/b/id/{$b['cover_id']}-M.jpg";
        } elseif (!empty($b['cover_i'])) {
            $img = "https://covers.openlibrary.org/b/id/{$b['cover_i']}-M.jpg";
        } else {
            $img = '';
        }

        $price = 20.00;
    ?>
        <div style="border:1px solid #ccc;margin:8px;padding:8px;width:180px">
            <h4><?= htmlspecialchars($title) ?></h4>
            <p><i><?= htmlspecialchars($author_list) ?></i></p>
            <?php if ($img): ?>
                <img src="<?= htmlspecialchars($img) ?>" height="120"><br>
            <?php endif; ?>
            <p>US$ <?= number_format($price,2) ?></p>
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
