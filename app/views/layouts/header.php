<?php
$blogCategories = getCategoriesByType('blog');
?>

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
            <div class="menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <nav class="navbar">
                <a href="<?= BASE_URL ?>">Home</a>
                <div class="nav-item has-dropdown">
                    <a href="<?= BASE_URL ?>/blog">Blog</a>

                    <?php if (!empty($blogCategories)): ?>
                        <div class="dropdown-menu">
                            <?php foreach ($blogCategories as $cat): ?>
                                <a href="<?= BASE_URL ?>/blog/<?= $cat['slug']; ?>">
                                    <?= $cat['name']; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <a href="<?= BASE_URL ?>/zine">Bulletin Sastra</a>
                <a href="<?= BASE_URL ?>/katalog">Katalog</a>
                <a href="<?= BASE_URL ?>/page/jual-beli">Jual Beli</a>
                <a href="<?= BASE_URL ?>/page/event">Event</a>
                <a href="<?= BASE_URL ?>/page/about">About</a>
                <a href="<?= BASE_URL ?>/page/contribute">Contribute</a>
            </nav>
            <?php if (isLoggedIn()): ?>
                <a href="<?= BASE_URL ?>/<?= isAdmin() ? 'admin' : 'contributor' ?>" class="profile-icon" title="Dashboard">
                    <i class="fa-solid fa-user"></i>
                </a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/auth/login" class="profile-icon" title="Login">
                    <i class="fa-regular fa-user"></i>
                </a>
            <?php endif; ?>
        </div>
        <div class="mobile-menu">
            <div class="mobile-menu-header">
                <span class="close">X</span>
            </div>
            <ul>
                <li><a href="<?= BASE_URL ?>">Home</a></li>
                <li class="has-sub">
                    <a href="<?= BASE_URL ?>/blog" class="mobile-toggle">
                        Blog <span class="icon">></span>
                    </a>

                    <?php if (!empty($blogCategories)): ?>
                        <ul class="sub-menu">
                            <?php foreach ($blogCategories as $cat): ?>
                                <li>
                                    <a href="<?= BASE_URL ?>/blog/<?= $cat['slug']; ?>">
                                        <?= $cat['name']; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
                <li><a href="<?= BASE_URL ?>/zine">Bulletin Sastra</a></li>
                <li><a href="<?= BASE_URL ?>/katalog">Katalog</a></li>
                <li><a href="<?= BASE_URL ?>/page/jual-beli">Jual Beli</a></li>
                <li><a href="<?= BASE_URL ?>/page/event">Event</a></li>
                <li><a href="<?= BASE_URL ?>/page/about">About</a></li>
                <li><a href="<?= BASE_URL ?>/page/contribute">Contribute</a></li>
            </ul>
        </div>
</header>
