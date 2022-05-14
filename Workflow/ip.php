<?php

use Alfred\Workflows\Workflow;

require_once('vendor/Workflow.php');
require_once('vendor/Result.php');
require_once('util/request.php');

const ICON = 'icon.png';
const ERROR_ICON = 'error.png';

$wf = new Workflow;

if (empty($query)) {
    $response = request('https://api.ip.sb/geoip');
} else {
    $response = request('https://api.ip.sb/geoip/' . urlencode($query));
}

$json = json_decode($response, true);

if (!empty($json['code'])) {
    $wf->result()
        ->title($json['code'])
        ->subtitle($json['message'])
        ->icon(ERROR_ICON)
        ->arg("");
} else {
    foreach ($json as $name => $value) {
        $wf->result()
            ->title(ucwords($name, '_') . ": " . $value)
            ->subtitle("Copy '{$value}' to clipboard")
            ->icon(ICON)
            ->arg($value);
    }
}

echo $wf->output();