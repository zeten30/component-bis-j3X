<?php

// No direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modelitem');

//We need content plugin BIS to be installed !!!
require_once (JPATH_BASE . DS . 'plugins' . DS . 'content' . DS . 'bis' . DS . 'myr' . DS . 'myr.php');

class bisModelBis extends JModelItem {

    private $session;

    function __construct() {
        $this->session = JFactory::getSession();
    }

    public function buildQuery($params) {
        //Get parameters
        $show_type = $params->get('show-type');
        $bis_id = $params->get('bis_id');
        $custom_query = $params->get('custom-query');
        $paging = $params->get('limit-count');
        $only_year = $params->get('only-year');
        $only_program = $params->get('only-program');

        //Ret value
        $ret = array('query' => '', 'result_modify' => '');


        //Custom query
        if ($show_type == 'custom') {
            if (strlen($custom_query) > 0) {
                $ret['query'] = $custom_query;
                return $ret;
            } else {
                $ret['query'] = 'query=akce';
                return $ret;
            }
        }

        //Base query
        switch ($show_type) {
            case 'all':
                $ret['query'] = 'query=akce';
                break;
            case 'tab':
                $tab = JRequest::getVar('id', $this->getLastTab($params));

                if ($tab < 1 || $tab > 5) {
                    $tab = 1;
                }

                $tabs = $this->setUpTabs($params);

                $ret['query'] = $tabs['query'][$tab];
                break;
            case 'vik':
                $ret['query'] = 'query=akce&filter=vik';
                break;
            case 'tabor':
                $ret['query'] = 'query=akce&filter=tabor';
                break;
            case 'ekostan':
                $ret['query'] = 'query=akce&filter=ekostan';
                break;
            case 'vikeko':
                $ret['query'] = 'query=akce&filter=vikekostan';
                break;
            case 'klub':
                $ret['query'] = 'query=akce&filter=klub';
                break;
        }

        if (strlen($bis_id) > 0) {
            $ret['query'].="&id=$bis_id";
        }

        //Additional params
        //Paging
        if (strlen($paging) > 0 && intval($paging) > 0) {
            $ret['query'].='&limit_from=0&limit_count=' . $paging;
        }

        //Only year
        if (strlen($only_year) > 0 && intval($only_year) > 1970) {
            $ret['query'].='&rok=' . $only_year;
        }


        $use_bis_filter = $params->get('use-mod-bis-filter');

        if ($use_bis_filter == 0) {
            //Only program
            if (strlen($only_program) > 0) {
                $ret['query'].='&program=' . $only_program;
            }
        } else {
            //Use options from mod BIS FILTER
            $ret['query'].=self::getBisFilterParams();
        }

        return $ret;
    }

    private function getBisFilterParams() {
        $mainframe = & JFactory::getApplication();
        $jinput = & JFactory::getApplication()->input;

        $date_from = self::getStatePostVariable($mainframe, $jinput, 'state_date_from', 'date');
        $date_to = self::getStatePostVariable($mainframe, $jinput, 'state_date_to', 'date-to');
        $for = self::getStatePostVariable($mainframe, $jinput, 'state_for', 'for');
        $program = self::getStatePostVariable($mainframe, $jinput, 'state_program', 'program');
        $organized = self::getStatePostVariable($mainframe, $jinput, 'state_organized', 'organized');
        $type = self::getStatePostVariable($mainframe, $jinput, 'state_type', 'type');

        $ret = "";

        if ($date_from != '' && $date_to != '') {

            if (preg_match('/\./', $date_from)) {
                $fmt_from = DateTime::createFromFormat('d.m.Y', $date_from)->format('Y-m-d');
                $fmt_to = DateTime::createFromFormat('d.m.Y', $date_to)->format('Y-m-d');
            }

            if (preg_match('/\//', $date_from)) {
                $fmt_from = DateTime::createFromFormat('m/d/Y', $date_from)->format('Y-m-d');
                $fmt_to = DateTime::createFromFormat('m/d/Y', $date_to)->format('Y-m-d');
            }

            $ret.="&od=$fmt_from&do=$fmt_to";
        }

        if ($organized != '') {
            $ret.="&zc[]=$organized";
        }

        if ($program != '') {
            $ret.="&program=$program";
        }

        if ($for != '') {
            $ret.="&pro=$for";
        }

        if ($type != '') {
            $ret.="&typ=$type";
        }

        if (strlen($ret) > 0) {
            $mainframe->setUserState('state_mod_bis_filter_is_used', true);
        } else {
            $mainframe->setUserState('state_mod_bis_filter_is_used', false);
        }

        return $ret;
    }

    private function getStatePostVariable($mainframe, $jinput, $name, $post_name) {
        $var = $mainframe->getUserState($name, '');
        $post_var = $jinput->get('filter-by-' . $post_name, '##not set##');

        if ($post_var != '##not set##') {
            $var = $post_var;
        }

        return $var;
    }

    //Get result
    public function getResult($query) {
        $myr = new myr;
        $result = $myr->doQuery($query);
        return $result;
    }

    public function getFormattedResult($result, $params, $detail = false) {
        $template = new myrTemplateData;

        if ($detail == false) {
            $template->head = $params->get('tpl-head');
            $template->item = $params->get('tpl-item');
            $template->foot = $params->get('tpl-foot');
        } else {
            $template->head = $params->get('tpl-head-detail');
            $template->item = $params->get('tpl-item-detail');
            $template->foot = $params->get('tpl-foot-detail');
        }

        $url = $this->getSelfUrl('detail');

        //SEF or classic URL
        //Is SEF used?
        $app = & JFactory::getApplication(); //Get application
        if ($app->getCfg('sef') == 1) {
            $url .= '/';
        } else {
            $url .= '&amp;id=';
        }

        //Set up links
        $links['detail-url'] = $url;
        $links['detail-url-vik'] = $url;
        $links['detail-url-tabor'] = $url;
        $links['detail-url-klub'] = $url;
        $links['detail-url-eko'] = $url;
        $links['detail-url-vikeko'] = $url;

        $template->link_detail = $links;

        $myr = new myr;
        return $myr->displayFormattedResult($result, $template);
    }

    public function tabMenu($params, $active = 1) {
        $tabs = $this->setUpTabs($params);
        $rows = '';

        for ($i = 1; $i < 6; $i++) {
            if ($tabs['names'][$i] != '') {
                $class = '';

                if ($active == $i) {
                    $class = " active";
                    //Save last displayed tab in session var
                    $this->setLastTab($i, $params);
                }

                $rows.='<td class="tab' . $i . $class . '">
          <a href="' . $this->getSelfUrl('list', $i) . '">
            ' . JText::_($tabs['names'][$i]) . '
          </a>
          </td>';
            }
        }

        $ret = '';

        if (strlen($rows) > 0) {
            $ret = '
      <table id="com_bis_tabMenu">
        <tr>' . $rows . '</tr>
      </table>';
        }

        return $ret;
    }

    public function getSelfUrl($view = 'list', $id = null) {
        $app = & JFactory::getApplication(); //get JApplication
        $Itemid = $app->input->get('Itemid'); //get Itemid

        $routeString = 'index.php?option=com_bis&amp;view=' . $view . '&amp;Itemid=' . $Itemid;

        if ($id != null) {
            $routeString.='&amp;id=' . $id;
        }

        $url = JRoute::_($routeString);

        return $url;
    }

    private function setUpTabs($params) {
        $camp_tab_order = $params->get('show-camp-tab');
        $vik_tab_order = $params->get('show-vik-tab');
        $club_tab_order = $params->get('show-club-tab');
        $eko_tab_order = $params->get('show-eko-tab');
        $vikeko_tab_order = $params->get('show-vikeko-tab');

        $tabs = array('names' => array(0 => '',
                1 => '',
                2 => '',
                3 => '',
                4 => '',
                5 => ''),
            'query' => array(0 => '',
                1 => '',
                2 => '',
                3 => '',
                4 => '',
                5 => ''));

        $tabs['names'][$camp_tab_order] = 'COM_BIS_CAMPS';
        $tabs['names'][$vik_tab_order] = 'COM_BIS_VIKS';
        $tabs['names'][$club_tab_order] = 'COM_BIS_CLUBS';
        $tabs['names'][$eko_tab_order] = 'COM_BIS_EKOSTAN';
        $tabs['names'][$vikeko_tab_order] = 'COM_BIS_VIKS';

        $tabs['query'][$camp_tab_order] = 'query=akce&filter=tabor';
        $tabs['query'][$vik_tab_order] = 'query=akce&filter=vik';
        $tabs['query'][$club_tab_order] = 'query=akce&filter=klub';
        $tabs['query'][$eko_tab_order] = 'query=akce&filter=ekostan';
        $tabs['query'][$vikeko_tab_order] = 'query=akce&filter=vikekostan';

        return $tabs;
    }

    public function setLastTab($tab, $params) {
        $sync_tabs = $params->get('sync-tab-mod-bis');
        if ($sync_tabs == 1) {
            setcookie("mod_bis_actual_tab", $tab - 1, 0, '/');
        }

        $this->session->set('com_bis_last_tab', $tab);
    }

    public function getLastTab($params) {
        $sync_tabs = $params->get('sync-tab-mod-bis');

        if ($sync_tabs == 1) {
            if (isset($_COOKIE['mod_bis_actual_tab'])) {
                return $_COOKIE['mod_bis_actual_tab'] + 1;
            } else {
                return 1;
            }
        } else {
            return $this->session->get('com_bis_last_tab', 1);
        }
    }

    public function setCaller($caller) {
        $this->session->set('com_bis_caller', $caller);
    }

    public function getCaller() {
        return $this->session->get('com_bis_caller', '');
    }

    public function modBisFilterIsUsed() {
        $mainframe = & JFactory::getApplication();
        $filter_used = $mainframe->getUserState('state_mod_bis_filter_is_used', false);
        return $filter_used;
    }

    public function saveApplicationForm($givenname, $surname, $phonenumber, $email, $birthdate, $ad_info, $comment, $id_akce, $org_email, $event_name) {

        $myr = new myr;

        $url = $myr->params->get('bis_url') . '?query=prihlaska';

        if ($myr->params->get('bis_user') != '') {
            $url.='&user=' . $myr->params->get('bis_user');
        }

        $post_data = array(
            'jmeno' => $givenname,
            'prijmeni' => $surname,
            'telefon' => $phonenumber,
            'email' => $email,
            'akce' => $id_akce,
            'datum_narozeni' => $birthdate,
            'add_info' => $ad_info,
            'poznamka' => $comment,
            'user' => $myr->params->get('bis_user'),
            'password' => $myr->params->get('bis_password')
        );

        foreach ($post_data as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        $fields_string = substr($fields_string, 0, strlen($fields_string) - 1);

        //open connection
        $ch = curl_init();

//set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($post_data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

//execute post
        $result = curl_exec($ch);

        curl_close($ch);


        //Send e-mails\
        $mailer = JFactory::getMailer();
        $config = JFactory::getConfig();
        $sender = array(
            $config->getValue('config.mailfrom'),
            $config->getValue('config.fromname'));

        $mailer->setSender($sender);

        $recipient = array($org_email, $email);
        $mailer->addRecipient($recipient);

        $body = "Prihlaska na akci: $id_akce - $event_name \n\n
        Jmeno : $givenname \n
        Prijmeni : $surname \n
        Telefon : $phonenumber \n
        E-mail : $email \n
        Datum_narozeni : $birthdate \n
        Doplnujici dotaz : $ad_info \n
        Poznamka : $comment \n";

        $mailer->setSubject("Prihlaska na akci: $id_akce - $event_name");
        $mailer->setBody($body);

        $send = $mailer->Send();
        if ($send !== true) {
            echo 'Error sending email - Check config or your SMTP server.';
        }

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

}
