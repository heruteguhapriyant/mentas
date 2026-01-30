<?php
session_start();

require_once '../app/config/config.php';
require_once '../app/config/database.php';
require_once '../app/core/Database.php';
require_once '../app/core/Router.php';

/**
 * LOAD MODELS
 */
require_once '../app/models/User.php';
require_once '../app/models/Category.php';
require_once '../app/models/Post.php';
require_once '../app/models/Tag.php';
require_once '../app/models/Zine.php';
require_once '../app/models/Community.php';
require_once '../app/models/Comment.php';
require_once '../app/models/Product.php';
require_once '../app/models/Event.php';
require_once '../app/models/Ticket.php';

/**
 * LOAD GLOBAL HELPERS
 */
require_once '../app/helpers/content_helper.php';
require_once '../app/helpers/url_helper.php';
require_once '../app/helpers/auth_helper.php';

$router = new Router();
$router->dispatch();

