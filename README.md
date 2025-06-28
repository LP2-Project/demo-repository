# 📚 Proyecto Catálogo de Libros de Ficción

En este proyecto estamos trabajando con una API pública de OpenLibrary, específicamente con la siguiente dirección:

🔗 [https://openlibrary.org/subjects/fiction.json?limit=4000](https://openlibrary.org/subjects/fiction.json?limit=4000)

Desde esa URL obtenemos hasta 4000 libros de ficción y usamos esa información para mostrar un catálogo dinámico en nuestra web. **No guardamos los libros en la base de datos**, sino que cada vez que un usuario accede, el sistema consulta directamente a la API de OpenLibrary.

## 🔎 ¿Qué datos usamos?
Solo extraemos lo necesario para el catálogo:
- 📖 Título del libro
- ✍️ Autor
- 🖼️ Imagen de portada
- 🏷️ Categoría

Estos datos se muestran de forma ordenada para que los usuarios puedan:
✅ Buscar libros  
✅ Filtrar por autor o categoría  
✅ Hacer pedidos desde la misma interfaz

Este enfoque nos permite trabajar con **datos reales y actualizados** sin tener que cargarlos manualmente, y además hace que el sistema sea más ligero y dinámico.

---

## 👨‍💻 Integrantes del equipo
- Huancaya Recines Ericsson  
- Salazar Tarazona Jorge  
- Torres Palomino Carlos  
- Dueñas Loyola Yhozira  
- Zegarra Pimentel Jhonny

---

¡Gracias por visitar nuestro proyecto! 🚀
