<?php
require_once('core/database_config.php');

header('content-type: application/json');
echo json_encode($serverList);