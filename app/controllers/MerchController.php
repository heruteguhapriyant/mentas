<?php
// MerchController - Handles Merch (Products) Frontend

class MerchController extends Controller {
    private $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    /**
     * Display all products
     */
    public function index($slug = null) {
        // If slug is passed, check if it's numeric (ID) or a slug
        if ($slug) {
            if (is_numeric($slug)) {
                return $this->detail($slug);
            }
            // Try to find by slug
            $product = $this->productModel->getBySlug($slug);
            if ($product) {
                return $this->showDetail($product);
            }
        }
        
        $categorySlug = isset($_GET['category']) ? $_GET['category'] : null;
        $categories = $this->productModel->getCategories();
        
        if ($categorySlug) {
            $products = $this->productModel->getByCategory($categorySlug);
        } else {
            $products = $this->productModel->getAll();
        }
        
        return $this->view('merch/index', [
            'products' => $products,
            'categories' => $categories,
            'activeCategory' => $categorySlug
        ]);
    }

    /**
     * Display single product detail by ID
     */
    public function detail($id) {
        // Check if it's numeric (ID) or string (slug)
        if (is_numeric($id)) {
            $product = $this->productModel->find(intval($id));
        } else {
            $product = $this->productModel->getBySlug($id);
        }
        
        if (!$product) {
            return $this->view('errors/404');
        }
        
        return $this->showDetail($product);
    }

    /**
     * Render product detail view
     */
    private function showDetail($product) {
        // Get related products (same category)
        $relatedProducts = [];
        if (!empty($product['category_slug'])) {
            $relatedProducts = $this->productModel->getByCategory($product['category_slug']);
            // Remove current product from related
            $relatedProducts = array_filter($relatedProducts, function($p) use ($product) {
                return $p['id'] !== $product['id'];
            });
            // Limit to 4
            $relatedProducts = array_slice($relatedProducts, 0, 4);
        }
        
        // Parse images JSON if exists
        $images = [];
        if (!empty($product['images'])) {
            $images = json_decode($product['images'], true) ?? [];
        }
        
        return $this->view('merch/detail', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'images' => $images
        ]);
    }

    /**
     * Generate WhatsApp order link
     */
    public function order($slug) {
        $product = $this->productModel->getBySlug($slug);
        
        if (!$product) {
            http_response_code(404);
            echo json_encode(['error' => 'Product not found']);
            return;
        }
        
        // Format price
        $price = number_format($product['price'], 0, ',', '.');
        
        // Create WhatsApp message
        $message = "Halo, saya mau order:\n\n";
        $message .= "*{$product['name']}*\n";
        $message .= "Harga: Rp {$price}\n\n";
        $message .= "Link: " . BASE_URL . "/merch/detail/{$product['id']}\n\n";
        $message .= "Mohon informasi ketersediaan stok. Terima kasih!";
        
        // Get WhatsApp number from product or default
        $whatsappNumber = $product['whatsapp_number'] ?? '6283895189649';
        
        // Generate WhatsApp URL
        $whatsappUrl = "https://wa.me/{$whatsappNumber}?text=" . urlencode($message);
        
        header("Location: {$whatsappUrl}");
        exit;
    }

    /**
     * Magic method to handle /merch/{slug} URLs
     */
    public function __call($name, $arguments) {
        // Check if it's an order URL: /merch/order/{slug}
        if ($name === 'order' && !empty($arguments[0])) {
            return $this->order($arguments[0]);
        }
        
        // Otherwise treat the method name as a product slug or ID
        return $this->detail($name);
    }
}
