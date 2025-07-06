<?php
// api.php
require_once __DIR__ . '/controladores/ApiController.php';

$api = new ApiController();
echo $api->importarLibros();
