<?php
// CartController - Shopping Cart Management

class CartController extends Controller {
    
    public function __construct() {
        // Ensure session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Initialize cart if not exists
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    /**
     * Display cart page
     */
    public function index() {
        $cartItems = $this->getCartWithProducts();
        $total = $this->getCartTotal();
        
        return $this->view('cart/index', [
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }

    /**
     * Add product to cart (AJAX)
     */
    public function add() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }
        
        $productId = intval($_POST['product_id'] ?? 0);
        $quantity = intval($_POST['quantity'] ?? 1);
        
        if ($productId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Produk tidak valid']);
            return;
        }
        
        // Check product exists and has stock
        $productModel = new Product();
        $product = $productModel->find($productId);
        
        if (!$product) {
            echo json_encode(['success' => false, 'message' => 'Produk tidak ditemukan']);
            return;
        }
        
        if ($product['stock'] <= 0) {
            echo json_encode(['success' => false, 'message' => 'Stok produk habis']);
            return;
        }
        
        // Add or update cart
        if (isset($_SESSION['cart'][$productId])) {
            $newQty = $_SESSION['cart'][$productId] + $quantity;
            // Check stock limit
            if ($newQty > $product['stock']) {
                $newQty = $product['stock'];
            }
            $_SESSION['cart'][$productId] = $newQty;
        } else {
            $_SESSION['cart'][$productId] = min($quantity, $product['stock']);
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Produk ditambahkan ke keranjang',
            'cartCount' => $this->getCartCount(),
            'product' => [
                'name' => $product['name'],
                'price' => $product['price']
            ]
        ]);
    }

    /**
     * Update quantity (AJAX)
     */
    public function update() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }
        
        $productId = intval($_POST['product_id'] ?? 0);
        $quantity = intval($_POST['quantity'] ?? 1);
        
        if ($productId <= 0 || !isset($_SESSION['cart'][$productId])) {
            echo json_encode(['success' => false, 'message' => 'Produk tidak ada di keranjang']);
            return;
        }
        
        if ($quantity <= 0) {
            // Remove item if quantity is 0
            unset($_SESSION['cart'][$productId]);
        } else {
            // Check stock limit
            $productModel = new Product();
            $product = $productModel->find($productId);
            if ($product && $quantity > $product['stock']) {
                $quantity = $product['stock'];
            }
            $_SESSION['cart'][$productId] = $quantity;
        }
        
        echo json_encode([
            'success' => true,
            'cartCount' => $this->getCartCount(),
            'total' => $this->getCartTotal()
        ]);
    }

    /**
     * Remove item from cart (AJAX)
     */
    public function remove() {
        header('Content-Type: application/json');
        
        $productId = intval($_POST['product_id'] ?? $_GET['product_id'] ?? 0);
        
        if ($productId > 0 && isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
        
        echo json_encode([
            'success' => true,
            'cartCount' => $this->getCartCount(),
            'total' => $this->getCartTotal()
        ]);
    }

    /**
     * Clear cart
     */
    public function clear() {
        $_SESSION['cart'] = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'cartCount' => 0]);
            return;
        }
        
        header('Location: ' . BASE_URL . '/cart');
        exit;
    }

    /**
     * Get cart count (AJAX)
     */
    public function count() {
        header('Content-Type: application/json');
        echo json_encode(['count' => $this->getCartCount()]);
    }

    /**
     * Checkout - Generate WhatsApp message
     */
    public function checkout() {
        $cartItems = $this->getCartWithProducts();
        
        if (empty($cartItems)) {
            setFlash('error', 'Keranjang belanja kosong');
            header('Location: ' . BASE_URL . '/merch');
            exit;
        }
        
        // Build WhatsApp message
        $message = "Halo, saya ingin memesan:\n\n";
        $total = 0;
        
        foreach ($cartItems as $item) {
            $subtotal = $item['product']['price'] * $item['quantity'];
            $total += $subtotal;
            
            $message .= "• {$item['product']['name']}\n";
            $message .= "  Qty: {$item['quantity']} x Rp " . number_format($item['product']['price'], 0, ',', '.') . "\n";
            $message .= "  Subtotal: Rp " . number_format($subtotal, 0, ',', '.') . "\n\n";
        }
        
        $message .= "─────────────────\n";
        $message .= "TOTAL: Rp " . number_format($total, 0, ',', '.') . "\n\n";
        $message .= "Mohon informasi untuk proses pembayaran dan pengiriman. Terima kasih!";
        
        // Get WhatsApp number
        $whatsappNumber = '6283895189649';
        
        // Clear cart after checkout
        $_SESSION['cart'] = [];
        
        // Redirect to WhatsApp
        $whatsappUrl = "https://wa.me/{$whatsappNumber}?text=" . urlencode($message);
        header("Location: {$whatsappUrl}");
        exit;
    }

    // ==================
    // HELPER METHODS
    // ==================

    /**
     * Get cart items with product details
     */
    private function getCartWithProducts() {
        if (empty($_SESSION['cart'])) {
            return [];
        }
        
        $productModel = new Product();
        $items = [];
        
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $product = $productModel->find($productId);
            if ($product) {
                $items[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $product['price'] * $quantity
                ];
            }
        }
        
        return $items;
    }

    /**
     * Get total cart count
     */
    private function getCartCount() {
        return array_sum($_SESSION['cart'] ?? []);
    }

    /**
     * Get cart total price
     */
    private function getCartTotal() {
        $total = 0;
        $cartItems = $this->getCartWithProducts();
        
        foreach ($cartItems as $item) {
            $total += $item['subtotal'];
        }
        
        return $total;
    }
}
