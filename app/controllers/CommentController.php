<?php

class CommentController extends Controller
{
    private $commentModel;

    // ─── KONFIGURASI FILTER ──────────────────────────────────────────────────

    // Kata/frasa spam (case-insensitive)
    private $spamKeywords = [
        // Promosi & judi
        'slot', 'gacor', 'casino', 'poker', 'togel', 'betting', 'jackpot',
        'sbobet', 'pragmatic', 'zeus slot', 'olympus',
        // Pinjol & keuangan mencurigakan
        'pinjaman online', 'dana cepat', 'kredit tanpa', 'bunga rendah',
        // Obat & konten dewasa
        'obat kuat', 'viagra', 'cialis', 'porn', 'xxx', 'bokep',
        // Frasa spam umum
        'click here', 'klik disini', 'klik di sini', 'buy now', 'free money',
        'make money', 'work from home', 'passive income', 'investment opportunity',
        // Phishing umum
        'verify your account', 'suspended', 'confirm your', 'login here',
        'update your', 'limited time offer',
    ];

    // Ekstensi domain yang sering dipakai spam
    private $suspiciousTlds = [
        '.xyz', '.top', '.click', '.loan', '.win', '.bid',
        '.review', '.party', '.date', '.stream',
    ];

    // Rate limit: maks komentar per IP per menit
    private $rateLimitMax    = 3;
    private $rateLimitWindow = 60; // detik

    // ────────────────────────────────────────────────────────────────────────

    public function __construct()
    {
        $this->commentModel = new Comment();
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/blog');
            exit;
        }

        $data = [
            'post_id' => $_POST['post_id'],
            'name'    => trim($_POST['name'] ?? ''),
            'email'   => trim($_POST['email'] ?? ''),
            'body'    => trim($_POST['body'] ?? ''),
            'user_id' => $_SESSION['user_id'] ?? null,
        ];

        if (isset($_SESSION['user_id'])) {
            $data['name']  = $_SESSION['user_name'];
            $data['email'] = $_SESSION['user_email'];
        }

        // ── 1. Validasi field kosong ─────────────────────────────────────────
        if (empty($data['name']) || empty($data['email']) || empty($data['body'])) {
            setFlash('error', 'Semua kolom harus diisi.');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        // ── 2. Rate limiting per IP ──────────────────────────────────────────
        $ip = $this->getClientIp();
        if ($this->isRateLimited($ip)) {
            setFlash('error', 'Anda mengirim komentar terlalu cepat. Tunggu sebentar.');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        // ── 3. Panjang komentar ──────────────────────────────────────────────
        $bodyLength = mb_strlen($data['body']);
        if ($bodyLength < 5) {
            setFlash('error', 'Komentar terlalu pendek.');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
        if ($bodyLength > 2000) {
            setFlash('error', 'Komentar terlalu panjang (maksimal 2000 karakter).');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        // ── 4. Deteksi URL & link ────────────────────────────────────────────
        if ($this->containsUrl($data['body'])) {
            setFlash('error', 'Komentar tidak boleh mengandung URL atau tautan.');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        // ── 5. Deteksi karakter asing (non-latin) ────────────────────────────
        if ($this->containsForeignScript($data['body'])) {
            setFlash('error', 'Komentar mengandung karakter yang tidak didukung.');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        // ── 6. Deteksi kata kunci spam ───────────────────────────────────────
        $spamWord = $this->containsSpamKeyword($data['body'] . ' ' . $data['name']);
        if ($spamWord) {
            setFlash('error', 'Komentar terdeteksi sebagai spam.');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        // ── 7. Deteksi pengulangan karakter berlebihan (aaaaaa, !!!!) ────────
        if ($this->hasExcessiveRepetition($data['body'])) {
            setFlash('error', 'Komentar mengandung karakter yang berulang secara tidak wajar.');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        // ── 8. Simpan & catat rate limit ────────────────────────────────────
        $this->recordRequest($ip);

        if ($this->commentModel->create($data)) {
            setFlash('success', 'Komentar berhasil dikirim.');
        } else {
            setFlash('error', 'Gagal mengirim komentar.');
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // ═══════════════════════════════════════════════════════════════════════
    // HELPER METHODS
    // ═══════════════════════════════════════════════════════════════════════

    /**
     * Cek apakah teks mengandung URL atau link
     */
    private function containsUrl(string $text): bool
    {
        // Protokol eksplisit
        if (preg_match('/https?:\/\//i', $text)) return true;
        if (preg_match('/ftp:\/\//i', $text))   return true;

        // www tanpa protokol
        if (preg_match('/\bwww\./i', $text)) return true;

        // Pola domain umum: kata.com / kata.net / dll
        if (preg_match('/\b\w+\.(com|net|org|id|co\.id|io|info|biz|gov|edu)\b/i', $text)) return true;

        // TLD mencurigakan
        foreach ($this->suspiciousTlds as $tld) {
            if (stripos($text, $tld) !== false) return true;
        }

        // Markup HTML/BBCode link
        if (preg_match('/<a\s/i', $text))       return true;
        if (preg_match('/\[url/i', $text))       return true;

        return false;
    }

    /**
     * Deteksi skrip non-latin: Cyrillic, Arab, Cina, Jepang, Korea, dll
     */
    private function containsForeignScript(string $text): bool
    {
        // Cyrillic (Rusia, dll)
        if (preg_match('/[\x{0400}-\x{04FF}]/u', $text)) return true;
        // Arab & turunannya
        if (preg_match('/[\x{0600}-\x{06FF}]/u', $text)) return true;
        // CJK (Cina, Jepang, Korea)
        if (preg_match('/[\x{4E00}-\x{9FFF}]/u', $text)) return true;
        if (preg_match('/[\x{3040}-\x{30FF}]/u', $text)) return true; // Hiragana/Katakana
        if (preg_match('/[\x{AC00}-\x{D7AF}]/u', $text)) return true; // Hangul
        // Thai
        if (preg_match('/[\x{0E00}-\x{0E7F}]/u', $text)) return true;

        return false;
    }

    /**
     * Cek kata kunci spam di teks
     */
    private function containsSpamKeyword(string $text): bool
    {
        $textLower = mb_strtolower($text);
        foreach ($this->spamKeywords as $keyword) {
            if (str_contains($textLower, mb_strtolower($keyword))) {
                return true;
            }
        }
        return false;
    }

    /**
     * Deteksi pengulangan karakter berlebihan: "aaaaaaa", "!!!!!", "hahahahaha"
     */
    private function hasExcessiveRepetition(string $text): bool
    {
        // Karakter sama berulang > 5x berturut-turut
        if (preg_match('/(.)\1{5,}/', $text)) return true;

        // Kata pendek berulang > 4x (haha, wkwk, dsb dikecualikan sampai 4x)
        if (preg_match('/(\b\w{1,4}\b)(\s*\1){5,}/i', $text)) return true;

        return false;
    }

    /**
     * Ambil IP client (support proxy)
     */
    private function getClientIp(): string
    {
        foreach (['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $key) {
            if (!empty($_SERVER[$key])) {
                // Ambil IP pertama jika ada koma
                return trim(explode(',', $_SERVER[$key])[0]);
            }
        }
        return '0.0.0.0';
    }

    /**
     * Cek apakah IP sedang kena rate limit
     */
    private function isRateLimited(string $ip): bool
    {
        $key = 'comment_rl_' . md5($ip);
        if (!isset($_SESSION[$key])) return false;

        $record = $_SESSION[$key];
        $now    = time();

        // Reset jika window sudah lewat
        if ($now - $record['start'] > $this->rateLimitWindow) {
            unset($_SESSION[$key]);
            return false;
        }

        return $record['count'] >= $this->rateLimitMax;
    }

    /**
     * Catat request komentar untuk rate limiting
     */
    private function recordRequest(string $ip): void
    {
        $key = 'comment_rl_' . md5($ip);
        $now = time();

        if (!isset($_SESSION[$key]) || ($now - $_SESSION[$key]['start'] > $this->rateLimitWindow)) {
            $_SESSION[$key] = ['start' => $now, 'count' => 1];
        } else {
            $_SESSION[$key]['count']++;
        }
    }
}