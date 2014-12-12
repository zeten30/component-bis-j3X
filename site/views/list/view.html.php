<?php

// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');
jimport('joomla.html.html');

class bisViewList extends JView {

    function display($tpl = null) {

        $mainframe = JFactory::getApplication();
        $params = clone($mainframe->getParams());

        $model = new bisModelBis;
        $query = $model->buildQuery($params);
        $result = $model->getResult($query['query']);
        $formatted_res = $model->getFormattedResult($result, $params);

        if ($params->get('show-type') == 'tab') {
            $tab_id = JRequest::getVar('id', $model->getLastTab($params));

            if ($tab_id < 1 || $tab_id > 5) {
                $tab_id = 1;
            }

            $tabMenu = $model->tabMenu($params, $tab_id);
            $this->assignRef('tabMenu', $tabMenu);
        }

        $this->assignRef('result', $formatted_res);
        $this->assignRef('params', $params);

        $this->assignRef('mod_bis_filter_used', $model->modBisFilterIsUsed());

        $model->setCaller($model->getSelfUrl('list', JRequest::getVar('id', null)));

        parent::display($tpl);
    }

}
