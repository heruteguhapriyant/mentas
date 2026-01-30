<?php
// PentasController - Handles Events and Ticketing

class PentasController extends Controller {
    private $eventModel;
    private $ticketModel;

    public function __construct() {
        $this->eventModel = new Event();
        $this->ticketModel = new Ticket();
    }

    /**
     * Display all events or single event detail
     */
    public function index($idOrSlug = null) {
        // If parameter is passed, show detail page
        if ($idOrSlug) {
            return $this->detail($idOrSlug);
        }
        
        $upcomingEvents = $this->eventModel->getUpcoming(20);
        $pastEvents = $this->eventModel->getPast(10);
        
        return $this->view('pentas/index', [
            'upcomingEvents' => $upcomingEvents,
            'pastEvents' => $pastEvents
        ]);
    }

    /**
     * Display single event detail
     */
    public function detail($idOrSlug) {
        // Check if it's numeric (ID) or string (slug)
        if (is_numeric($idOrSlug)) {
            $event = $this->eventModel->find(intval($idOrSlug));
        } else {
            $event = $this->eventModel->getBySlug($idOrSlug);
        }
        
        if (!$event) {
            return $this->view('errors/404');
        }
        
        $availableTickets = $this->eventModel->getAvailableTickets($event['id']);
        $confirmedCount = $this->ticketModel->countByStatusForEvent($event['id'], 'confirmed');
        
        return $this->view('pentas/detail', [
            'event' => $event,
            'availableTickets' => $availableTickets,
            'confirmedCount' => $confirmedCount
        ]);
    }

    /**
     * Display registration form and process registration
     */
    public function register($idOrSlug) {
        // Check if it's numeric (ID) or string (slug)
        if (is_numeric($idOrSlug)) {
            $event = $this->eventModel->find(intval($idOrSlug));
        } else {
            $event = $this->eventModel->getBySlug($idOrSlug);
        }
        
        if (!$event) {
            return $this->view('errors/404');
        }
        
        $eventSlug = !empty($event['slug']) ? $event['slug'] : $event['id'];
        
        // Handle POST request - process registration
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->processRegistration($event, $eventSlug);
        }
        
        // Check if event is full
        if (!$this->eventModel->hasAvailableTickets($event['id'])) {
            setFlash('error', 'Maaf, tiket untuk event ini sudah habis.');
            header("Location: " . BASE_URL . "/pentas/{$eventSlug}");
            exit;
        }
        
        $paymentSettings = $this->ticketModel->getPaymentSettings();
        $availableTickets = $this->eventModel->getAvailableTickets($event['id']);
        
        return $this->view('pentas/register', [
            'event' => $event,
            'paymentSettings' => $paymentSettings,
            'availableTickets' => $availableTickets
        ]);
    }
    
    /**
     * Process registration form submission
     */
    private function processRegistration($event, $eventSlug) {
        // Validate input
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $quantity = (int)($_POST['quantity'] ?? 1);
        
        if (empty($name) || empty($email)) {
            setFlash('error', 'Nama dan email wajib diisi.');
            header("Location: " . BASE_URL . "/pentas/register/{$eventSlug}");
            exit;
        }
        
        if ($quantity < 1 || $quantity > 10) {
            $quantity = 1;
        }
        
        // Calculate total price
        $totalPrice = $event['ticket_price'] * $quantity;
        
        // Handle payment proof upload if price > 0
        $paymentProof = null;
        if ($totalPrice > 0 && isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] === 0) {
            $uploadDir = __DIR__ . '/../../public/uploads/payments/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $ext = pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION);
            $filename = 'payment_' . time() . '_' . uniqid() . '.' . $ext;
            $targetPath = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['payment_proof']['tmp_name'], $targetPath)) {
                $paymentProof = 'uploads/payments/' . $filename;
            }
        }
        
        // Create ticket
        $ticketData = [
            'event_id' => $event['id'],
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'quantity' => $quantity,
            'total_price' => $totalPrice,
            'payment_proof' => $paymentProof,
            'status' => $totalPrice == 0 ? 'confirmed' : 'pending'
        ];
        
        $ticketCode = $this->ticketModel->create($ticketData);
        
        if ($ticketCode) {
            // Update event tickets sold
            $this->eventModel->incrementTicketsSold($event['id'], $quantity);
            
            // Redirect to ticket confirmation
            header("Location: " . BASE_URL . "/pentas/ticket/{$ticketCode}");
            exit;
        } else {
            setFlash('error', 'Gagal mendaftar. Silakan coba lagi.');
            header("Location: " . BASE_URL . "/pentas/register/{$eventSlug}");
            exit;
        }
    }

    /**
     * Process registration form
     */
    public function processRegister($slug) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/pentas/{$slug}");
            exit;
        }
        
        $event = $this->eventModel->getBySlug($slug);
        if (!$event) {
            http_response_code(404);
            return;
        }
        
        // Validate input
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $quantity = (int)($_POST['quantity'] ?? 1);
        
        if (empty($name) || empty($email)) {
            setFlash('error', 'Nama dan email wajib diisi.');
            header("Location: " . BASE_URL . "/pentas/register/{$slug}");
            exit;
        }
        
        if ($quantity < 1 || $quantity > 10) {
            $quantity = 1;
        }
        
        // Calculate total price
        $totalPrice = $event['ticket_price'] * $quantity;
        
        // Handle payment proof upload if price > 0
        $paymentProof = null;
        if ($totalPrice > 0 && isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] === 0) {
            $uploadDir = __DIR__ . '/../../public/uploads/payments/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $ext = pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION);
            $filename = 'payment_' . time() . '_' . uniqid() . '.' . $ext;
            $targetPath = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['payment_proof']['tmp_name'], $targetPath)) {
                $paymentProof = 'uploads/payments/' . $filename;
            }
        }
        
        // Create ticket
        $ticketData = [
            'event_id' => $event['id'],
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'quantity' => $quantity,
            'total_price' => $totalPrice,
            'payment_proof' => $paymentProof,
            'status' => $totalPrice == 0 ? 'confirmed' : 'pending'
        ];
        
        $ticketCode = $this->ticketModel->create($ticketData);
        
        if ($ticketCode) {
            // Update event tickets sold
            $this->eventModel->incrementTicketsSold($event['id'], $quantity);
            
            // Redirect to ticket confirmation
            header("Location: " . BASE_URL . "/pentas/ticket/{$ticketCode}");
            exit;
        } else {
            setFlash('error', 'Gagal mendaftar. Silakan coba lagi.');
            header("Location: " . BASE_URL . "/pentas/register/{$slug}");
            exit;
        }
    }

    /**
     * Display ticket confirmation
     */
    public function ticket($code) {
        $ticket = $this->ticketModel->getByCode($code);
        
        if (!$ticket) {
            return $this->view('errors/404');
        }
        
        return $this->view('pentas/ticket', [
            'ticket' => $ticket
        ]);
    }

    /**
     * Check-in ticket via QR
     */
    public function checkin($code = null) {
        if (!$code && isset($_GET['code'])) {
            $code = $_GET['code'];
        }
        
        header('Content-Type: application/json');
        
        if (!$code) {
            echo json_encode(['success' => false, 'message' => 'Kode tiket tidak ditemukan']);
            return;
        }
        
        $ticket = $this->ticketModel->getByCode($code);
        
        if (!$ticket) {
            echo json_encode(['success' => false, 'message' => 'Tiket tidak valid']);
            return;
        }
        
        if ($ticket['status'] === 'checked_in') {
            echo json_encode([
                'success' => false, 
                'message' => 'Tiket sudah di-check-in sebelumnya',
                'checked_in_at' => $ticket['checked_in_at']
            ]);
            return;
        }
        
        if ($ticket['status'] !== 'confirmed') {
            echo json_encode(['success' => false, 'message' => 'Tiket belum dikonfirmasi']);
            return;
        }
        
        // Process check-in
        if ($this->ticketModel->checkIn($code)) {
            echo json_encode([
                'success' => true,
                'message' => 'Check-in berhasil!',
                'ticket' => [
                    'code' => $code,
                    'name' => $ticket['name'],
                    'quantity' => $ticket['quantity'],
                    'event' => $ticket['event_title']
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal melakukan check-in']);
        }
    }

    /**
     * Magic method to handle dynamic URLs
     */
    public function __call($name, $arguments) {
        // Handle /pentas/register/{slug}
        if ($name === 'register' && !empty($arguments[0])) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                return $this->processRegister($arguments[0]);
            }
            return $this->register($arguments[0]);
        }
        
        // Handle /pentas/ticket/{code}
        if ($name === 'ticket' && !empty($arguments[0])) {
            return $this->ticket($arguments[0]);
        }
        
        // Handle /pentas/checkin/{code}
        if ($name === 'checkin') {
            return $this->checkin($arguments[0] ?? null);
        }
        
        // Otherwise treat as event slug
        return $this->detail($name);
    }
}
