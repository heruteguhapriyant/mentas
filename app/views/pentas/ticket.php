<?php 
$pageTitle = "Tiket - " . htmlspecialchars($ticket['event_title']) . " | Mentas.id";
require_once __DIR__ . '/../layouts/header.php'; 
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pentas.css">

<main class="ticket-page">
    <div class="container">
        <div class="ticket-container">
            <!-- Ticket Card -->
            <div class="ticket-card">
                <div class="ticket-header">
                    <img src="<?= BASE_URL ?>/assets/images/Mentas-logo.png" alt="Mentas" class="ticket-logo">
                    <span class="ticket-label">E-TICKET</span>
                </div>

                <div class="ticket-body">
                    <h1 class="ticket-event-title"><?= htmlspecialchars($ticket['event_title']) ?></h1>
                    
                    <div class="ticket-info-grid">
                        <div class="info-item">
                            <span class="label">Tanggal</span>
                            <span class="value"><?= date('d F Y', strtotime($ticket['event_date'])) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Waktu</span>
                            <span class="value"><?= date('H:i', strtotime($ticket['event_date'])) ?> WIB</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Lokasi</span>
                            <span class="value"><?= htmlspecialchars($ticket['venue'] ?? 'TBA') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Jumlah</span>
                            <span class="value"><?= $ticket['quantity'] ?> tiket</span>
                        </div>
                    </div>

                    <div class="ticket-divider"></div>

                    <div class="ticket-holder">
                        <span class="label">Atas Nama</span>
                        <span class="holder-name"><?= htmlspecialchars($ticket['name']) ?></span>
                        <span class="holder-email"><?= htmlspecialchars($ticket['email']) ?></span>
                    </div>

                    <div class="ticket-code-section">
                        <span class="label">Kode Tiket</span>
                        <span class="ticket-code"><?= $ticket['ticket_code'] ?></span>
                    </div>

                    <!-- QR Code -->
                    <div class="qr-section">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?= urlencode(BASE_URL . '/pentas/checkin/' . $ticket['ticket_code']) ?>" 
                             alt="QR Code" class="qr-code">
                        <p class="qr-instruction">Tunjukkan QR code ini saat check-in</p>
                    </div>
                </div>

                <div class="ticket-footer">
                    <div class="ticket-status status-<?= $ticket['status'] ?>">
                        <?php 
                        $statusLabels = [
                            'pending' => '<i class="fas fa-clock"></i> Menunggu Konfirmasi',
                            'confirmed' => '<i class="fas fa-check-circle"></i> Dikonfirmasi',
                            'cancelled' => '<i class="fas fa-times-circle"></i> Dibatalkan',
                            'checked_in' => '<i class="fas fa-user-check"></i> Sudah Check-in'
                        ];
                        echo $statusLabels[$ticket['status']] ?? $ticket['status'];
                        ?>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="ticket-instructions">
                <?php if ($ticket['status'] === 'pending'): ?>
                    <div class="instruction-box warning">
                        <h3><i class="fas fa-exclamation-triangle"></i> Menunggu Konfirmasi</h3>
                        <p>Pembayaran Anda sedang diverifikasi oleh admin. Anda akan menerima notifikasi melalui email setelah tiket dikonfirmasi.</p>
                    </div>
                <?php elseif ($ticket['status'] === 'confirmed'): ?>
                    <div class="instruction-box success">
                        <h3><i class="fas fa-check-circle"></i> Tiket Dikonfirmasi</h3>
                        <p>Tiket Anda sudah dikonfirmasi. Silakan simpan halaman ini atau screenshot QR code untuk ditunjukkan saat check-in di lokasi event.</p>
                    </div>
                <?php elseif ($ticket['status'] === 'checked_in'): ?>
                    <div class="instruction-box info">
                        <h3><i class="fas fa-user-check"></i> Sudah Check-in</h3>
                        <p>Anda sudah melakukan check-in pada <?= date('d M Y H:i', strtotime($ticket['checked_in_at'])) ?>. Selamat menikmati event!</p>
                    </div>
                <?php endif; ?>

                <div class="action-buttons">
                    <button onclick="window.print()" class="btn-print">
                        <i class="fas fa-print"></i> Cetak Tiket
                    </button>
                    <a href="<?= BASE_URL ?>/pentas" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Kembali ke Pentas
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
@media print {
    /* Hide unnecessary elements */
    .header, .footer, .ticket-instructions, .breadcrumb, nav, .action-buttons { 
        display: none !important; 
    }
    
    /* Reset page */
    html, body {
        margin: 0 !important;
        padding: 0 !important;
        height: auto !important;
        background: #fff !important;
    }
    
    /* Main container */
    .ticket-page {
        padding: 0 !important;
        margin: 0 !important;
        min-height: auto !important;
        background: #fff !important;
    }
    
    .container {
        max-width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    
    .ticket-container {
        max-width: 100% !important;
        margin: 0 !important;
    }
    
    /* Ticket card styling */
    .ticket-card { 
        box-shadow: none !important; 
        border: 2px solid #333;
        border-radius: 10px !important;
        margin: 20px auto !important;
        max-width: 400px !important;
        page-break-inside: avoid !important;
    }
    
    .ticket-header {
        background: #d52c2c !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    
    .ticket-body {
        padding: 20px !important;
    }
    
    .ticket-event-title {
        font-size: 1.2rem !important;
        margin-bottom: 15px !important;
    }
    
    .ticket-info-grid {
        gap: 10px !important;
        margin-bottom: 15px !important;
    }
    
    .info-item {
        padding: 10px !important;
    }
    
    .qr-code {
        width: 150px !important;
        height: 150px !important;
    }
    
    .ticket-footer {
        padding: 15px !important;
    }
    
    .ticket-status {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    
    /* Page settings */
    @page {
        size: A4;
        margin: 10mm;
    }
}
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
