<?php
require_once __DIR__ . '/../Config/Database.php';

class ProductModel extends Database {

    // 1. Lấy tất cả sản phẩm (Có tìm kiếm và sửa lỗi connection)
    public function getAll($keyword = '') {
        $sql = "SELECT * FROM products";
        
        // Nếu có từ khóa tìm kiếm
        if (!empty($keyword)) {
            // Chỉ tìm theo tên (name) để tránh lỗi nếu không có cột description
            $sql .= " WHERE name LIKE :keyword";
        }
        
        $sql .= " ORDER BY id DESC"; 
        
        // Dùng $this->conn (PDO) chuẩn
        $stmt = $this->conn->prepare($sql);
        
        if (!empty($keyword)) {
            $stmt->bindValue(':keyword', '%' . $keyword . '%');
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Lấy Sản phẩm Bán chạy (Top 10 views cao nhất)
    public function getBestSellers() {
        $sql = "SELECT * FROM products ORDER BY views DESC LIMIT 50";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3. Lấy Sản phẩm Mới nhất (Top 10 ID mới nhất)
    public function getNewArrivals() {
        $sql = "SELECT * FROM products ORDER BY id DESC LIMIT 4";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 4. Lấy chi tiết 1 sản phẩm
    public function getOne($id) {
        $sql = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 5. THÊM SẢN PHẨM
    public function insert($name, $price, $old_price, $discount, $image, $unit, $category_id, $brand_id, $quantity, $views, $is_new,$description) {
        $sql = "INSERT INTO products (name, price, old_price, discount, image, unit, category_id, brand_id, quantity, views, is_new, description) 
                VALUES (:name, :price, :old_price, :discount, :image, :unit, :category_id, :brand_id, :quantity, :views, :is_new,:description)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'name' => $name, 
            'price' => $price, 
            'old_price' => $old_price, 
            'discount' => $discount, 
            'image' => $image, 
            'unit' => $unit, 
            'category_id' => $category_id, 
            'brand_id' => $brand_id, 
            'quantity' => $quantity,
            'views' => $views, 
            'is_new' => $is_new,
            'description' => $description
        ]);
    }

    // 6. CẬP NHẬT SẢN PHẨM
    public function update($id, $name, $price, $old_price, $discount, $image, $unit, $category_id, $brand_id, $quantity, $views, $is_new,$description) {
        if ($image) {
            $sql = "UPDATE products SET name=:name, price=:price, old_price=:old_price, discount=:discount, 
                    image=:image, unit=:unit, category_id=:category_id, brand_id=:brand_id, quantity=:quantity, views=:views, is_new=:is_new, description=:description WHERE id=:id";
            $params = [
                'id' => $id, 
                'name' => $name, 
                'price' => $price, 
                'old_price' => $old_price, 
                'discount' => $discount, 
                'image' => $image, 
                'unit' => $unit, 
                'category_id' => $category_id, 
                'brand_id' => $brand_id, 
                'quantity' => $quantity,
                'views' => $views, 
                'is_new' => $is_new,
                'description' => $description
            ];
        } else {
            $sql = "UPDATE products SET name=:name, price=:price, old_price=:old_price, discount=:discount, 
                    unit=:unit, category_id=:category_id, brand_id=:brand_id, quantity=:quantity, views=:views, is_new=:is_new, description=:description WHERE id=:id";
            $params = [
                'id' => $id, 
                'name' => $name, 
                'price' => $price, 
                'old_price' => $old_price, 
                'discount' => $discount, 
                'unit' => $unit, 
                'category_id' => $category_id, 
                'brand_id' => $brand_id, 
                'quantity' => $quantity,
                'views' => $views, 
                'is_new' => $is_new,
                'description' => $description
            ];
        }
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    // 7. Xóa sản phẩm
    public function delete($id) {
        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // 8. Lấy sản phẩm liên quan (Đã giữ lại hàm này và xóa hàm trùng ở cuối)
    public function getRelated($categoryId, $currentId) {
        $sql = "SELECT * FROM products WHERE category_id = :cat_id AND id != :id ORDER BY RAND() LIMIT 4";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['cat_id' => $categoryId, 'id' => $currentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 9. Tìm kiếm sản phẩm theo tên
    public function searchByName($keyword) {
        $sql = "SELECT * FROM products WHERE name LIKE :keyword ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['keyword' => '%' . $keyword . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // 10. Tìm kiếm Admin (Hỗ trợ tìm theo ID hoặc Tên)
    public function searchAdmin($keyword) {
        $sql = "SELECT * FROM products WHERE name LIKE :keyword OR id = :id ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'keyword' => '%' . $keyword . '%',
            'id' => is_numeric($keyword) ? $keyword : -1
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 11. Thống kê sản phẩm bán chạy (Top Selling dựa trên đơn hàng đã hoàn thành)
    public function getTopSelling() {
        // Lưu ý: Cần đảm bảo bảng order_details có cột 'num' (số lượng)
        $sql = "SELECT p.id, p.name, p.image, p.price, SUM(od.num) as total_sold 
                FROM products p 
                JOIN order_details od ON p.id = od.product_id 
                JOIN orders o ON od.order_id = o.id
                WHERE o.status = 3
                GROUP BY p.id 
                ORDER BY total_sold DESC 
                LIMIT 10";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 12. Thống kê sản phẩm tồn kho (Chưa bán được cái nào)
    public function getUnsoldProducts() {
        $sql = "SELECT * FROM products 
                WHERE id NOT IN (SELECT DISTINCT product_id FROM order_details)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 13. Lọc sản phẩm nâng cao
    public function getFilteredProducts($keyword, $cat_id, $price_min, $price_max, $sort) {
        $sql = "SELECT * FROM products WHERE 1=1"; 
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND name LIKE :keyword";
            $params['keyword'] = "%$keyword%";
        }

        if (!empty($cat_id)) {
            $sql .= " AND category_id = :cat_id";
            $params['cat_id'] = $cat_id;
        }

        if (!empty($price_min)) {
            $sql .= " AND price >= :price_min";
            $params['price_min'] = $price_min;
        }
        if (!empty($price_max)) {
            $sql .= " AND price <= :price_max";
            $params['price_max'] = $price_max;
        }

        if ($sort == 'price_asc') {
            $sql .= " ORDER BY price ASC";
        } elseif ($sort == 'price_desc') {
            $sql .= " ORDER BY price DESC";
        } elseif ($sort == 'newest') {
            $sql .= " ORDER BY id DESC";
        } else {
            $sql .= " ORDER BY id DESC"; 
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // 14. Trừ số lượng tồn kho sau khi bán
    public function decreaseStock($productId, $quantitySold) {
        // Lệnh SQL: Cập nhật quantity = quantity hiện tại - số lượng bán
        $sql = "UPDATE products SET quantity = quantity - :qty WHERE id = :id";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'qty' => $quantitySold,
            'id'  => $productId
        ]);
    }
    public function getSuggestedProducts($limit = 60) {
        // Cách 1: Lấy ngẫu nhiên (Khuyên dùng cho mục Gợi ý)
        //$sql = "SELECT * FROM products ORDER BY RAND() LIMIT $limit";
        
        // Cách 2: Nếu bạn muốn lấy tất cả sản phẩm mới nhất thì dùng dòng dưới (Bỏ comment):
         $sql = "SELECT * FROM products ORDER BY id DESC LIMIT $limit";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getProductsByCategory($categoryId, $limit = 20) {
        $sql = "SELECT * FROM products WHERE category_id = :cat_id ORDER BY id DESC LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':cat_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    
}
}

?>