<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

// Require the base controller
require_once (JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'controller.php');
//Require model
require_once (JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'bis.php');

// Get an instance of the controller
$controller = JControllerLegacy::getInstance('Bis');

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();



