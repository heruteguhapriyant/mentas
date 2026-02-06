<?php

/**
 * ZineController - Bulletin Sastra (Updated dengan Pagination)
 */
class ZineController extends Controller
{
    private $zineModel;
    private $itemsPerPage = 6; // Jumlah zine per halaman

    public function __construct()
    {
        $this->zineModel = new Zine();
    }

    /**
     * List all zines with optional category filter and pagination
     */
    public function index()
    {
        $categorySlug = $_GET['category'] ?? null;
        $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        
        // Get categories for filter
        $categories = $this->zineModel->getCategories();
        
        // Count total zines
        $totalZines = $this->zineModel->countAll($categorySlug);
        
        // Generate pagination data
        $pagination = paginate($totalZines, $currentPage, $this->itemsPerPage);
        
        // Get paginated zines
        if ($categorySlug) {
            $zines = $this->zineModel->getByCategory(
                $categorySlug,
                $pagination['items_per_page'],
                $pagination['offset']
            );
        } else {
            $zines = $this->zineModel->all(
                $pagination['items_per_page'],
                $pagination['offset']
            );
        }
        
        return $this->view('zine/index', [
            'zines' => $zines,
            'categories' => $categories,
            'activeCategory' => $categorySlug,
            'pagination' => $pagination
        ]);
    }

    /**
     * View single zine
     */
    public function detail($slug)
    {
        $zine = $this->zineModel->findBySlug($slug);
        
        if (!$zine) {
            return $this->view('errors/404');
        }

        // Get related zines (same category, exclude current)
        $relatedZines = [];
        if (!empty($zine['category_slug'])) {
            $allCategoryZines = $this->zineModel->getByCategory($zine['category_slug'], 5);
            $relatedZines = array_filter($allCategoryZines, function($z) use ($zine) {
                return $z['id'] !== $zine['id'];
            });
            $relatedZines = array_slice($relatedZines, 0, 4);
        }

        return $this->view('zine/detail', [
            'zine' => $zine,
            'relatedZines' => $relatedZines
        ]);
    }

    /**
     * Download zine PDF (optional - untuk tracking download)
     */
    public function download($slug)
    {
        $zine = $this->zineModel->findBySlug($slug);
        
        if (!$zine || empty($zine['pdf_file'])) {
            return $this->view('errors/404');
        }

        // Increment download count (jika ada field di database)
        if (method_exists($this->zineModel, 'incrementDownloads')) {
            $this->zineModel->incrementDownloads($zine['id']);
        }

        // Redirect to PDF file atau serve file
        $pdfPath = BASE_PATH . '/public/' . $zine['pdf_file'];
        
        if (file_exists($pdfPath)) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . basename($pdfPath) . '"');
            header('Content-Length: ' . filesize($pdfPath));
            readfile($pdfPath);
            exit;
        } else {
            return $this->view('errors/404');
        }
    }
}