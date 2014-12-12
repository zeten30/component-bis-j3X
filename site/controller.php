<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * Component BIS Controller
 */
class BisController extends JControllerLegacy {

    function display() {
        //Set the default view, just in case
        $view = JRequest::getCmd('view');
        if (empty($view)) {
            JRequest::setVar('view', 'list');
        }

        parent::display();
    }

}
