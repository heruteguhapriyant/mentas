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
     * Display all events with search & pagination, or single event detail
     */
    public function index($idOrSlug = null) {
        if ($idOrSlug) {
            return $this->detail($idOrSlug);
        }
    
        $search   = trim($_GET['search'] ?? '');
        $pageUp   = max(1, (int)($_GET['page_upcoming'] ?? 1));
        $pagePast = max(1, (int)($_GET['page_past'] ?? 1));
        $perPage  = 9;
    
        $totalUpcoming  = $this->eventModel->countUpcomingWithSchedules($search);
        $paginationUp   = paginate($totalUpcoming, $pageUp, $perPage);
        $upcomingEvents = $this->eventModel->getUpcomingWithSchedules($search, $paginationUp['offset'], $perPage);
    
        $totalPast      = $this->eventModel->countPastWithSchedules($search);
        $paginationPast = paginate($totalPast, $pagePast, $perPage);
        $pastEvents     = $this->eventModel->getPastWithSchedules($search, $paginationPast['offset'], $perPage);
    
        // Lampirkan semua jadwal ke setiap event
        foreach ($upcomingEvents as &$ev) {
            $ev['schedules'] = $this->eventModel->getSchedules($ev['id']);
        }
        foreach ($pastEvents as &$ev) {
            $ev['schedules'] = $this->eventModel->getSchedules($ev['id']);
        }
        unset($ev);
    
        return $this->view('pentas/index', [
            'upcomingEvents'     => $upcomingEvents,
            'pastEvents'         => $pastEvents,
            'paginationUpcoming' => $paginationUp,
            'paginationPast'     => $paginationPast,
            'searchQuery'        => $search,
        ]);
    }

    /**
     * Display single event detail
     */
    public function detail($idOrSlug) {
        if (is_numeric($idOrSlug)) {
            $event = $this->eventModel->find(intval($idOrSlug));
        } else {
            $event = $this->eventModel->getBySlug($idOrSlug);
        }
    
        if (!$event) {
            return $this->view('errors/404');
        }
    
        $schedules         = $this->eventModel->getSchedules($event['id']);
        $availableTickets  = $this->eventModel->getAvailableTickets($event['id']);
        $confirmedCount    = $this->ticketModel->countByStatusForEvent($event['id'], 'confirmed');
        $eventContributors = $this->eventModel->getEventContributorsDetail($event['id']);
    
        return $this->view('pentas/detail', [
            'event'             => $event,
            'schedules'         => $schedules,
            'availableTickets'  => $availableTickets,
            'confirmedCount'    => $confirmedCount,
            'eventContributors' => $eventContributors,
        ]);
    }

    /**
     * Display registration form and process registration
     */
    public function register($idOrSlug) {
        if (is_numeric($idOrSlug)) {
            $event = $this->eventModel->find(intval($idOrSlug));
        } else {
            $event = $this->eventModel->getBySlug($idOrSlug);
        }

        if (!$event) {
            return $this->view('errors/404');
        }

        $eventSlug = !empty($event['slug']) ? $event['slug'] : $event['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->processRegistration($event, $eventSlug);
        }

        if (!$this->eventModel->hasAvailableTickets($event['id'])) {
            setFlash('error', 'Maaf, tiket untuk event ini sudah habis.');
            header("Location: " . BASE_URL . "/pentas/{$eventSlug}");
            exit;
        }

        $paymentSettings  = $this->ticketModel->getPaymentSettings();
        $availableTickets = $this->eventModel->getAvailableTickets($event['id']);

        return $this->view('pentas/register', [
            'event'            => $event,
            'paymentSettings'  => $paymentSettings,
            'availableTickets' => $availableTickets,
        ]);
    }

    /**
     * Process registration form submission
     */
    private function processRegistration($event, $eventSlug) {
        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $phone    = trim($_POST['phone'] ?? '');
        $quantity = (int)($_POST['quantity'] ?? 1);

        if (empty($name) || empty($email)) {
            setFlash('error', 'Nama dan email wajib diisi.');
            header("Location: " . BASE_URL . "/pentas/register/{$eventSlug}");
            exit;
        }

        if ($quantity < 1 || $quantity > 10) {
            $quantity = 1;
        }

        $totalPrice   = $event['ticket_price'] * $quantity;
        $paymentProof = null;

        if ($totalPrice > 0 && isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] === 0) {
            $uploadDir = dirname(__DIR__, 2) . '/public/uploads/payments/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $ext        = pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION);
            $filename   = 'payment_' . time() . '_' . uniqid() . '.' . $ext;
            $targetPath = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['payment_proof']['tmp_name'], $targetPath)) {
                $paymentProof = 'uploads/payments/' . $filename;
            }
        }

        $ticketData = [
            'event_id'      => $event['id'],
            'name'          => $name,
            'email'         => $email,
            'phone'         => $phone,
            'quantity'      => $quantity,
            'total_price'   => $totalPrice,
            'payment_proof' => $paymentProof,
            'status'        => $totalPrice == 0 ? 'confirmed' : 'pending',
        ];

        $ticketCode = $this->ticketModel->create($ticketData);

        if ($ticketCode) {
            $this->eventModel->incrementTicketsSold($event['id'], $quantity);
            header("Location: " . BASE_URL . "/pentas/ticket/{$ticketCode}");
            exit;
        } else {
            setFlash('error', 'Gagal mendaftar. Silakan coba lagi.');
            header("Location: " . BASE_URL . "/pentas/register/{$eventSlug}");
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
            'ticket' => $ticket,
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
                'success'       => false,
                'message'       => 'Tiket sudah di-check-in sebelumnya',
                'checked_in_at' => $ticket['checked_in_at'],
            ]);
            return;
        }

        if ($ticket['status'] !== 'confirmed') {
            echo json_encode(['success' => false, 'message' => 'Tiket belum dikonfirmasi']);
            return;
        }

        if ($this->ticketModel->checkIn($code)) {
            echo json_encode([
                'success' => true,
                'message' => 'Check-in berhasil!',
                'ticket'  => [
                    'code'     => $code,
                    'name'     => $ticket['name'],
                    'quantity' => $ticket['quantity'],
                    'event'    => $ticket['event_title'],
                ],
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal melakukan check-in']);
        }
    }

    /**
     * Magic method to handle dynamic URLs
     */
    public function __call($name, $arguments) {
        if ($name === 'register' && !empty($arguments[0])) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                return $this->processRegister($arguments[0]);
            }
            return $this->register($arguments[0]);
        }

        if ($name === 'ticket' && !empty($arguments[0])) {
            return $this->ticket($arguments[0]);
        }

        if ($name === 'checkin') {
            return $this->checkin($arguments[0] ?? null);
        }

        return $this->detail($name);
    }
}