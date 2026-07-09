<?php
require_once '/var/www/ptf-services-stage/vendor/autoload.php';

use App\Services\PlayerService;
use App\Repositories\PlayerRepository;

header('Content-Type: application/json');

$service = new PlayerService(new PlayerRepository());

echo json_encode(
    $service->getAll()
);