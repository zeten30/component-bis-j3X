<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * Component BIS Controller
 */
class BisController extends JController {

    function display($cachable = false) {
        // set default view if not set
        JRequest::setVar('view', JRequest::getCmd('view', 'Bis'));

        // call parent behavior
        parent::display($cachable);
    }

}
