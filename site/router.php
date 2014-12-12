<?php

defined('_JEXEC') or die('Restricted access');

function BisBuildRoute(&$query) {
    $segments = array();
    if (isset($query['view'])) {
        $segments[] = $query['view'];
        unset($query['view']);
    }
    if (isset($query['id'])) {
        $segments[] = $query['id'];
        unset($query['id']);
    };
    return $segments;
}

function BisParseRoute($segments) {
    $vars = array();
    switch ($segments[0]) {
        case 'list':
            $vars['view'] = 'list';
            $id = explode(':', $segments[1]);
            $vars['id'] = (int) $id[0];
            break;
        case 'detail':
            $vars['view'] = 'detail';
            $id = explode(':', $segments[1]);
            $vars['id'] = (int) $id[0];
            break;
    }
    return $vars;
}
