<?php
require_once '/var/www/ptf-services-stage/vendor/autoload.php';

use App\Services\TeamService;
use App\Repositories\TeamRepository;

header('Content-Type: application/json');

$service = new TeamService(new TeamRepository());

echo json_encode(
    $service->getAll()
);