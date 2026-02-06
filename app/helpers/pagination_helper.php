<?php
/**
 * Pagination Helper
 * Helper untuk menangani pagination di seluruh aplikasi
 */

if (!function_exists('paginate')) {
    /**
     * Generate pagination data
     * 
     * @param int $totalItems Total jumlah item
     * @param int $currentPage Halaman saat ini
     * @param int $itemsPerPage Jumlah item per halaman
     * @param int $maxPages Maksimal tombol page yang ditampilkan
     * @return array Data pagination
     */
    function paginate($totalItems, $currentPage = 1, $itemsPerPage = 9, $maxPages = 5)
    {
        // Validasi input
        $totalItems = max(0, (int)$totalItems);
        $currentPage = max(1, (int)$currentPage);
        $itemsPerPage = max(1, (int)$itemsPerPage);
        $maxPages = max(3, (int)$maxPages);

        // Hitung total halaman
        $totalPages = (int)ceil($totalItems / $itemsPerPage);
        
        // Pastikan current page tidak melebihi total pages
        $currentPage = min($currentPage, max(1, $totalPages));

        // Hitung offset untuk query database
        $offset = ($currentPage - 1) * $itemsPerPage;

        // Tentukan range halaman yang ditampilkan
        $startPage = max(1, $currentPage - floor($maxPages / 2));
        $endPage = min($totalPages, $startPage + $maxPages - 1);

        // Adjust start page jika end page sudah mentok
        if ($endPage - $startPage < $maxPages - 1) {
            $startPage = max(1, $endPage - $maxPages + 1);
        }

        // Generate page numbers array
        $pages = [];
        for ($i = $startPage; $i <= $endPage; $i++) {
            $pages[] = $i;
        }

        return [
            'total_items' => $totalItems,
            'total_pages' => $totalPages,
            'current_page' => $currentPage,
            'items_per_page' => $itemsPerPage,
            'offset' => $offset,
            'has_previous' => $currentPage > 1,
            'has_next' => $currentPage < $totalPages,
            'previous_page' => max(1, $currentPage - 1),
            'next_page' => min($totalPages, $currentPage + 1),
            'start_page' => $startPage,
            'end_page' => $endPage,
            'pages' => $pages,
            'showing_from' => $totalItems > 0 ? $offset + 1 : 0,
            'showing_to' => min($offset + $itemsPerPage, $totalItems)
        ];
    }
}

if (!function_exists('getPaginationUrl')) {
    /**
     * Generate URL dengan parameter page
     * 
     * @param int $page Nomor halaman
     * @param array $params Parameter tambahan
     * @return string URL lengkap
     */
    function getPaginationUrl($page, $params = [])
    {
        $currentUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $queryParams = $_GET;
        
        // Override dengan params yang diberikan
        $queryParams = array_merge($queryParams, $params);
        
        // Set page number
        if ($page > 1) {
            $queryParams['page'] = $page;
        } else {
            unset($queryParams['page']); // Hapus page=1 untuk URL yang lebih bersih
        }

        // Build query string
        if (!empty($queryParams)) {
            return $currentUrl . '?' . http_build_query($queryParams);
        }

        return $currentUrl;
    }
}

if (!function_exists('renderPagination')) {
    /**
     * Render HTML pagination
     * 
     * @param array $pagination Data pagination dari paginate()
     * @param array $options Opsi tambahan untuk custom class, dll
     * @return string HTML pagination
     */
    function renderPagination($pagination, $options = [])
    {
        // Jika hanya 1 halaman atau tidak ada data, jangan tampilkan pagination
        if ($pagination['total_pages'] <= 1) {
            return '';
        }

        $wrapperClass = $options['wrapper_class'] ?? 'pagination-wrapper';
        $activeClass = $options['active_class'] ?? 'active';
        $disabledClass = $options['disabled_class'] ?? 'disabled';

        ob_start();
        ?>
        <div class="<?= $wrapperClass ?>">
            <nav class="pagination" role="navigation" aria-label="Pagination">
                <!-- Previous Button -->
                <?php if ($pagination['has_previous']): ?>
                    <a href="<?= getPaginationUrl($pagination['previous_page']) ?>" 
                       class="pagination-btn pagination-prev"
                       aria-label="Previous page">
                        Previous
                    </a>
                <?php else: ?>
                    <span class="pagination-btn pagination-prev <?= $disabledClass ?>" 
                          aria-disabled="true">
                        Previous
                    </span>
                <?php endif; ?>

                <!-- Page Numbers -->
                <div class="pagination-numbers">
                    <?php foreach ($pagination['pages'] as $page): ?>
                        <?php if ($page == $pagination['current_page']): ?>
                            <span class="pagination-number <?= $activeClass ?>" 
                                  aria-current="page">
                                <?= $page ?>
                            </span>
                        <?php else: ?>
                            <a href="<?= getPaginationUrl($page) ?>" 
                               class="pagination-number"
                               aria-label="Go to page <?= $page ?>">
                                <?= $page ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <!-- Show dots if there are more pages -->
                    <?php if ($pagination['end_page'] < $pagination['total_pages']): ?>
                        <span class="pagination-dots">...</span>
                        <a href="<?= getPaginationUrl($pagination['total_pages']) ?>" 
                           class="pagination-number"
                           aria-label="Go to last page">
                            <?= $pagination['total_pages'] ?>
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Next Button -->
                <?php if ($pagination['has_next']): ?>
                    <a href="<?= getPaginationUrl($pagination['next_page']) ?>" 
                       class="pagination-btn pagination-next"
                       aria-label="Next page">
                        Next
                    </a>
                <?php else: ?>
                    <span class="pagination-btn pagination-next <?= $disabledClass ?>"
                          aria-disabled="true">
                        Next
                    </span>
                <?php endif; ?>
            </nav>

            <!-- Pagination Info (Optional) -->
            <div class="pagination-info">
                Menampilkan <?= $pagination['showing_from'] ?>-<?= $pagination['showing_to'] ?> 
                dari <?= $pagination['total_items'] ?> artikel
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}