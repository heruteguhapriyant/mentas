<header class="header">
    <div class="header-container">
        <div class="logo">
            <img src="<?= BASE_URL ?>/assets/images/Mentas-logo.png" 
                alt="Mentas Logo" 
                class="logo-default">
            <img src="<?= BASE_URL ?>/assets/images/mentas-putih.png" 
                alt="Mentas Logo White" 
                class="logo-sticky">
        </div>
        
        <nav class="navbar">
            <a href="<?= BASE_URL ?>">Home</a>
            <a href="<?= BASE_URL ?>/blog">Blog</a>
            <a href="<?= BASE_URL ?>/zine">Bulletin</a>
            <a href="<?= BASE_URL ?>/ekosistem">Ekosistem</a>
            <a href="<?= BASE_URL ?>/merch">Merch</a>
            <a href="<?= BASE_URL ?>/pentas">Pentas</a>
            <a href="<?= BASE_URL ?>/page/about">About</a>
            <a href="<?= BASE_URL ?>/page/contribute">Contribute</a>
        </nav>
        
        <div class="header-icons">
            <!-- User Icon -->
            <?php if (isLoggedIn()): ?>
                <a href="<?= BASE_URL ?>/<?= isAdmin() ? 'admin' : 'contributor' ?>" class="profile-icon" title="Dashboard">
                    <i class="fa-solid fa-user"></i>
                </a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/auth/login" class="profile-icon" title="Login">
                    <i class="fa-regular fa-user"></i>
                </a>
            <?php endif; ?>
            
            <!-- Burger Menu - Di paling kanan -->
            <div class="menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
    
    <div class="mobile-menu">
        <div class="mobile-menu-header">
            <!-- X sudah di handle oleh burger menu -->
        </div>
        <ul>
            <li><a href="<?= BASE_URL ?>">Home</a></li>
            <li><a href="<?= BASE_URL ?>/blog">Blog</a></li>
            <li><a href="<?= BASE_URL ?>/zine">Bulletin Sastra</a></li>
            <li><a href="<?= BASE_URL ?>/ekosistem">Ekosistem</a></li>
            <li><a href="<?= BASE_URL ?>/merch">Merch</a></li>
            <li><a href="<?= BASE_URL ?>/pentas">Pentas</a></li>
            <li><a href="<?= BASE_URL ?>/page/about">About</a></li>
            <li><a href="<?= BASE_URL ?>/page/contribute">Contribute</a></li>
        </ul>
    </div>
</header>