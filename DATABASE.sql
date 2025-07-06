CREATE DATABASE IF NOT EXISTS Libreria;
USE Libreria;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255),
    correo VARCHAR(255) UNIQUE,
    contrasena VARCHAR(255),
    tipo ENUM('administrador','empleado','cliente') DEFAULT 'cliente',
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255),
    descripcion TEXT,
    autor VARCHAR(255),
    precio DECIMAL(10,2),
    stock INT,
    imagen_url VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    estado ENUM('pendiente','preparado','listo','entregado') DEFAULT 'pendiente',
    total DECIMAL(10,2) DEFAULT 0,
    fecha_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE IF NOT EXISTS pedido_detalle (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    titulo VARCHAR(255),
    precio DECIMAL(10,2),
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE
);

ALTER TABLE pedidos DROP FOREIGN KEY pedidos_ibfk_1;

ALTER TABLE pedidos
ADD CONSTRAINT pedidos_ibfk_1
FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE;

INSERT INTO productos (nombre, descripcion, autor, precio, stock, imagen_url) VALUES
('El Gran Gatsby', 'Un clásico de la literatura estadounidense.', 'F. Scott Fitzgerald', 35.50, 10, 'https://covers.openlibrary.org/b/id/10523333-L.jpg'),
('Cien Años de Soledad', 'La obra maestra del realismo mágico.', 'Gabriel García Márquez', 42.00, 8, 'https://covers.openlibrary.org/b/id/11121219-L.jpg'),
('Don Quijote de la Mancha', 'La novela más influyente de la literatura española.', 'Miguel de Cervantes', 29.99, 12, 'https://covers.openlibrary.org/b/id/240727-L.jpg'),
('1984', 'Una distopía sobre el totalitarismo.', 'George Orwell', 31.25, 15, 'https://covers.openlibrary.org/b/id/7222246-L.jpg'),
('Matar a un Ruiseñor', 'Un relato sobre la injusticia racial.', 'Harper Lee', 28.40, 7, 'https://covers.openlibrary.org/b/id/8228691-L.jpg'),
('Orgullo y Prejuicio', 'Una de las historias de amor más famosas.', 'Jane Austen', 27.80, 9, 'https://covers.openlibrary.org/b/id/8081536-L.jpg'),
('Harry Potter y la Piedra Filosofal', 'El inicio de una saga mágica.', 'J.K. Rowling', 45.00, 20, 'https://covers.openlibrary.org/b/id/7984916-L.jpg'),
('El Hobbit', 'La aventura que precede a El Señor de los Anillos.', 'J.R.R. Tolkien', 33.00, 14, 'https://covers.openlibrary.org/b/id/6979861-L.jpg'),
('Los Juegos del Hambre', 'Un mundo distópico de supervivencia.', 'Suzanne Collins', 30.50, 11, 'https://covers.openlibrary.org/b/id/7261955-L.jpg'),
('Crónica de una Muerte Anunciada', 'Una novela breve y poderosa.', 'Gabriel García Márquez', 26.75, 10, 'https://covers.openlibrary.org/b/id/9553667-L.jpg'),
('La Sombra del Viento', 'Un thriller literario en la Barcelona de posguerra.', 'Carlos Ruiz Zafón', 39.90, 13, 'https://covers.openlibrary.org/b/id/5949421-L.jpg'),
('La Chica del Tren', 'Un thriller psicológico moderno.', 'Paula Hawkins', 32.40, 8, 'https://covers.openlibrary.org/b/id/8159550-L.jpg'),
('El Código Da Vinci', 'Un misterio lleno de símbolos.', 'Dan Brown', 35.20, 12, 'https://covers.openlibrary.org/b/id/240726-L.jpg'),
('Inferno', 'Otra aventura simbólica de Robert Langdon.', 'Dan Brown', 34.50, 9, 'https://covers.openlibrary.org/b/id/10582314-L.jpg'),
('It', 'Una historia de terror inolvidable.', 'Stephen King', 46.00, 7, 'https://covers.openlibrary.org/b/id/8231855-L.jpg'),
('Carrie', 'La primera novela de Stephen King.', 'Stephen King', 28.90, 10, 'https://covers.openlibrary.org/b/id/8091011-L.jpg'),
('Los Pilares de la Tierra', 'Una novela histórica épica.', 'Ken Follett', 44.60, 11, 'https://covers.openlibrary.org/b/id/10582416-L.jpg'),
('La Catedral del Mar', 'La construcción de una iglesia en la Barcelona medieval.', 'Ildefonso Falcones', 36.30, 9, 'https://covers.openlibrary.org/b/id/6939353-L.jpg'),
('Ready Player One', 'Un viaje al futuro de la realidad virtual.', 'Ernest Cline', 31.70, 14, 'https://covers.openlibrary.org/b/id/7572913-L.jpg'),
('Dune', 'Una epopeya de ciencia ficción.', 'Frank Herbert', 39.80, 8, 'https://covers.openlibrary.org/b/id/10523338-L.jpg'),
('Fahrenheit 451', 'Un futuro donde los libros están prohibidos.', 'Ray Bradbury', 25.50, 10, 'https://covers.openlibrary.org/b/id/11053970-L.jpg'),
('La Isla del Tesoro', 'Una clásica aventura de piratas.', 'Robert Louis Stevenson', 27.30, 12, 'https://covers.openlibrary.org/b/id/8231974-L.jpg'),
('La Odisea', 'La épica travesía de Ulises.', 'Homero', 29.00, 10, 'https://covers.openlibrary.org/b/id/7222245-L.jpg'),
('Hamlet', 'Una tragedia shakesperiana.', 'William Shakespeare', 26.00, 9, 'https://covers.openlibrary.org/b/id/10524518-L.jpg'),
('Romeo y Julieta', 'La historia de amor más famosa.', 'William Shakespeare', 25.00, 8, 'https://covers.openlibrary.org/b/id/10022509-L.jpg'),
('Macbeth', 'La ambición y sus consecuencias.', 'William Shakespeare', 24.50, 7, 'https://covers.openlibrary.org/b/id/10524768-L.jpg'),
('La Iliada', 'La epopeya de la guerra de Troya.', 'Homero', 30.00, 10, 'https://covers.openlibrary.org/b/id/8228757-L.jpg'),
('El Principito', 'Una historia universal sobre la infancia.', 'Antoine de Saint-Exupéry', 22.90, 15, 'https://covers.openlibrary.org/b/id/10523613-L.jpg'),
('El Alquimista', 'Un viaje de autodescubrimiento.', 'Paulo Coelho', 26.50, 13, 'https://covers.openlibrary.org/b/id/8315122-L.jpg'),
('Sapiens', 'Una breve historia de la humanidad.', 'Yuval Noah Harari', 35.80, 11, 'https://covers.openlibrary.org/b/id/10523601-L.jpg'),
('Homo Deus', 'El futuro de la humanidad.', 'Yuval Noah Harari', 37.20, 10, 'https://covers.openlibrary.org/b/id/10523606-L.jpg'),
('El Hombre en Busca de Sentido', 'La experiencia de un sobreviviente de Auschwitz.', 'Viktor Frankl', 29.90, 9, 'https://covers.openlibrary.org/b/id/10523820-L.jpg'),
('El Arte de la Guerra', 'El clásico tratado de estrategia.', 'Sun Tzu', 21.50, 14, 'https://covers.openlibrary.org/b/id/10523867-L.jpg'),
('Meditaciones', 'Los pensamientos de un emperador estoico.', 'Marco Aurelio', 23.40, 13, 'https://covers.openlibrary.org/b/id/10523895-L.jpg'),
('Los Hermanos Karamázov', 'Una novela filosófica sobre la moral.', 'Fiódor Dostoievski', 38.00, 8, 'https://covers.openlibrary.org/b/id/8232142-L.jpg'),
('Crimen y Castigo', 'La lucha de un hombre con su conciencia.', 'Fiódor Dostoievski', 36.50, 7, 'https://covers.openlibrary.org/b/id/8228748-L.jpg'),
('Anna Karenina', 'Una tragedia sobre el amor y la sociedad.', 'León Tolstói', 34.00, 10, 'https://covers.openlibrary.org/b/id/10523928-L.jpg'),
('Guerra y Paz', 'Una novela monumental sobre Rusia.', 'León Tolstói', 42.00, 6, 'https://covers.openlibrary.org/b/id/10523965-L.jpg'),
('La Metamorfosis', 'La historia de un hombre que despierta como insecto.', 'Franz Kafka', 24.00, 12, 'https://covers.openlibrary.org/b/id/10524014-L.jpg'),
('Ulises', 'Una odisea moderna por Dublín.', 'James Joyce', 40.00, 5, 'https://covers.openlibrary.org/b/id/10524105-L.jpg'),
('En Busca del Tiempo Perdido', 'Una obra monumental de introspección.', 'Marcel Proust', 45.00, 4, 'https://covers.openlibrary.org/b/id/10524134-L.jpg'),
('Lolita', 'Una novela controvertida sobre obsesión.', 'Vladimir Nabokov', 32.00, 7, 'https://covers.openlibrary.org/b/id/10524177-L.jpg'),
('El Nombre de la Rosa', 'Un misterio medieval en un monasterio.', 'Umberto Eco', 35.00, 8, 'https://covers.openlibrary.org/b/id/10524203-L.jpg'),
('Rayuela', 'Una novela experimental e icónica.', 'Julio Cortázar', 33.00, 9, 'https://covers.openlibrary.org/b/id/10524245-L.jpg'),
('Pedro Páramo', 'Una obra maestra del realismo mágico.', 'Juan Rulfo', 28.00, 10, 'https://covers.openlibrary.org/b/id/10524284-L.jpg'),
('El Amor en los Tiempos del Cólera', 'Una historia de amor que desafía el tiempo.', 'Gabriel García Márquez', 34.00, 8, 'https://covers.openlibrary.org/b/id/10524321-L.jpg'),
('La Casa de los Espíritus', 'Una saga familiar marcada por la magia.', 'Isabel Allende', 31.00, 11, 'https://covers.openlibrary.org/b/id/10524355-L.jpg'),
('Los Detectives Salvajes', 'Una novela sobre la juventud y la literatura en América Latina.', 'Roberto Bolaño', 36.00, 9, 'https://covers.openlibrary.org/b/id/10524388-L.jpg'),
('2666', 'Una obra compleja y ambiciosa sobre el mal y la literatura.', 'Roberto Bolaño', 44.50, 6, 'https://covers.openlibrary.org/b/id/10524415-L.jpg'),
('Ensayo sobre la Ceguera', 'Una alegoría sobre la sociedad y la pérdida de valores.', 'José Saramago', 30.70, 10, 'https://covers.openlibrary.org/b/id/10524442-L.jpg'),
('La Tregua', 'Un diario íntimo que narra una historia de amor y soledad.', 'Mario Benedetti', 27.80, 12, 'https://covers.openlibrary.org/b/id/10524463-L.jpg'),
('La Ciudad y los Perros', 'Una crítica a la sociedad militar y educativa del Perú.', 'Mario Vargas Llosa', 34.20, 11, 'https://covers.openlibrary.org/b/id/10524485-L.jpg');