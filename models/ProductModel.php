<?php
class ProductModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function searchProducts($query) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE name LIKE ?");
        $searchTerm = "%$query%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        return $stmt->get_result();
    }

    //  Додавання товару
    public function addProduct($data, $file) {
        $name = mysqli_real_escape_string($this->conn, $data['name']);
        $price = mysqli_real_escape_string($this->conn, $data['price']);
        $genre = mysqli_real_escape_string($this->conn, $data['genre']);
        $author = mysqli_real_escape_string($this->conn, $data['author']);
        $year = mysqli_real_escape_string($this->conn, $data['year_published']);
        $language = mysqli_real_escape_string($this->conn, $data['language']);
        $pages = mysqli_real_escape_string($this->conn, $data['number_pages']);
        $primary = mysqli_real_escape_string($this->conn, $data['primary_description']);
        $secondary = mysqli_real_escape_string($this->conn, $data['secondary_description']);

        $image = $file['image']['name'];
        $image_size = $file['image']['size'];
        $tmp_name = $file['image']['tmp_name'];
        $image_folder = '../uploaded_img/' . $image;

        $check = $this->conn->query("SELECT name FROM products WHERE name = '$name'");
        if ($check->num_rows > 0) {
            return 'Назва товару вже існує!';
        }

        $insert = $this->conn->query("INSERT INTO products(name, price, genre, author, year_published, language, number_pages, primary_description, secondary_description, image) 
        VALUES('$name', '$price', '$genre', '$author', '$year', '$language', '$pages', '$primary', '$secondary', '$image')");

        if ($insert) {
            if ($image_size > 2000000) {
                return 'Розмір зображення занадто великий!';
            } else {
                move_uploaded_file($tmp_name, $image_folder);
                return 'Товар успішно додано!';
            }
        }
        return 'Помилка при додаванні товару!';
    }

    //  Видалення товару
    public function deleteProduct($id) {
        $select = $this->conn->query("SELECT image FROM products WHERE id = '$id'");
        if ($select->num_rows > 0) {
            $row = $select->fetch_assoc();
            $image_path = '../uploaded_img/' . $row['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        $this->conn->query("DELETE FROM wishlist WHERE book_id = '$id'");
        $this->conn->query("DELETE FROM cart WHERE book_id = '$id'");
        $this->conn->query("DELETE FROM products WHERE id = '$id'");
    }

    //  Отримання всіх товарів
    public function getAllProducts() {
        $result = $this->conn->query("SELECT * FROM products ORDER BY id DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getProductById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateProduct($data, $files) {
        $id = $data['update_p_id'];
        $name = mysqli_real_escape_string($this->conn, $data['name']);
        $price = mysqli_real_escape_string($this->conn, $data['price']);
        $genre = mysqli_real_escape_string($this->conn, $data['genre']);
        $author = mysqli_real_escape_string($this->conn, $data['author']);
        $year = mysqli_real_escape_string($this->conn, $data['year_published']);
        $lang = mysqli_real_escape_string($this->conn, $data['language']);
        $pages = mysqli_real_escape_string($this->conn, $data['number_pages']);
        $desc1 = mysqli_real_escape_string($this->conn, $data['primary_description']);
        $desc2 = mysqli_real_escape_string($this->conn, $data['secondary_description']);

        // Оновлення полів
        $this->conn->query("
            UPDATE products SET 
            name = '$name',
            price = '$price',
            genre = '$genre',
            author = '$author',
            year_published = '$year',
            language = '$lang',
            number_pages = '$pages',
            primary_description = '$desc1',
            secondary_description = '$desc2'
            WHERE id = '$id'
        ");

        // Якщо є нове зображення
        if (!empty($files['image']['name'])) {
            $image = $files['image']['name'];
            $image_tmp = $files['image']['tmp_name'];
            $image_folder = '../uploaded_img/' . $image;

            // Отримуємо старе фото
            $oldImageRes = $this->conn->query("SELECT image FROM products WHERE id = '$id'");
            $oldImage = $oldImageRes->fetch_assoc()['image'];

            // Перезаписуємо
            move_uploaded_file($image_tmp, $image_folder);
            $this->conn->query("UPDATE products SET image = '$image' WHERE id = '$id'");
            if (file_exists('../uploaded_img/'.$oldImage)) {
                unlink('../uploaded_img/'.$oldImage);
            }
        }

        return "Товар успішно оновлено!";
    }
}
?>

