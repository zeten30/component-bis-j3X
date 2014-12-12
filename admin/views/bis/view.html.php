<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

class bisViewBis extends JView {

    function display($tpl = null) {
        JToolBarHelper::custom('com_bis', 'help');
        JToolBarHelper::title(JText::_('COM_BIS'), 'generic');
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        // Display the template
        parent::display($tpl);
    }

}
