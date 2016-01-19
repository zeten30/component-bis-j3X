<?php

// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class bisViewDetail extends JViewLegacy {

    function display($tpl = null) {

        $mainframe = JFactory::getApplication();
        $params = clone($mainframe->getParams());

        $model = new bisModelBis;
        $bis_id = JRequest::getVar('id');

        //if called directly as menu item - BIS ID is set by param
        if ($bis_id == NULL) {
            $bis_id = (int) $params->get('bis_id');
        }

        $params->set('show-type', "all");
        $params->set('bis_id', $bis_id);

        $query = $model->buildQuery($params);

        $result = $model->getResult($query['query']);
        $formatted_res = $model->getFormattedResult($result, $params, true);

        $this->assignRef('result', $formatted_res);
        $this->assignRef('params', $params);

        //Form results
        $form_sent = JRequest::getVar('app-form-sent');
        $empty_message = "";
        $success_message = "ok";
        $error_message = "error";

        //Org e-mail
        $org_email = $result->data->kontakt_email;
        $this->assignRef('org_email', $org_email);

        //Event name
        $event_name = $result->data->nazev;
        $this->assignRef('event_name', $event_name);

        //Handle form
        if ($form_sent == "true") {

            $givenname = JRequest::getVar('givenname');
            $surname = JRequest::getVar('surname');
            $phonenumber = JRequest::getVar('phonenumber');
            $email = JRequest::getVar('eladdr');
            $birthdate = JRequest::getVar('birthdate');
            $ad_info = JRequest::getVar('ad_info');
            $ad_info2 = JRequest::getVar('ad_info2');
            $ad_info3 = JRequest::getVar('ad_info3');
            $ad_title = JRequest::getVar('ad_title');
            $ad_title2 = JRequest::getVar('ad_title2');
            $ad_title3 = JRequest::getVar('ad_title3');
            $comment = JRequest::getVar('comment');
            $fake = JRequest::getVar('email');
            $org_email = JRequest::getVar('org-email');
            $event_name = JRequest::getVar('event-name');

            if (strlen($fake) < 1) {
                $app_form_result = $model
                        ->saveApplicationForm($givenname, $surname, $phonenumber, $email, $birthdate, $ad_info, $ad_info2, $ad_info3, $ad_title, $ad_title2, $ad_title3, $comment, $bis_id, $org_email, $event_name);
            }

            if ($app_form_result) {
                $this->assignRef('app_form_message', $success_message);
            } else {
                $this->assignRef('app_form_message', $error_message);
            }
        } else {
            $this->assignRef('app_form_message', $empty_message);
        }


        //Caller / Backlink handler
        if ($model->getCaller() != '') {
            $show_backlink = $params->get('show-backlink');

            $url = $model->getCaller() . "#event-" . $bis_id;

            if ($show_backlink == 1) {
                $this->assignRef('top_backlink', $url);
            }

            if ($show_backlink == 2) {
                $this->assignRef('bottom_backlink', $url);
            }

            if ($show_backlink == 3) {
                $this->assignRef('top_backlink', $url);
                $this->assignRef('bottom_backlink', $url);
            }
        } else {

            $self_url = $model->getSelfUrl();

            $server_name = $_SERVER['SERVER_NAME'];

            if ($_SERVER['HTTP_REFERER'] != null) {
                $url = $_SERVER['HTTP_REFERER'];

                if (!stristr($url, $server_name)) {
                    $url = $self_url;
                }
            } else {
                $url = $self_url;
            }

            $show_backlink = $params->get('show-backlink');

            if ($show_backlink == 1) {
                $this->assignRef('top_backlink', $url);
            }

            if ($show_backlink == 2) {
                $this->assignRef('bottom_backlink', $url);
            }

            if ($show_backlink == 3) {
                $this->assignRef('top_backlink', $url);
                $this->assignRef('bottom_backlink', $url);
            }
        }

        //Show application form?
        $show_appl_form = $params->get('show-bis-application-form');
        $this->assignRef('show_appl_form', $show_appl_form);

        //custom question for application form?
        $app_form_add_question = $result->data->add_info_title;
        $app_form_add_question2 = $result->data->add_info_title_2;
        $app_form_add_question3 = $result->data->add_info_title_3;
        $app_form_show = $result->data->adresar;

        if ($app_form_add_question != NULL) {
            $this->assignRef('app_form_add_question', $app_form_add_question);
        } else {
            $this->assignRef('app_form_add_question', $app_form_add_question);
        }

        if ($app_form_add_question2 != NULL) {
            $this->assignRef('app_form_add_question2', $app_form_add_question2);
        } else {
            $this->assignRef('app_form_add_question2', $app_form_add_question2);
        }

        if ($app_form_add_question3 != NULL) {
            $this->assignRef('app_form_add_question3', $app_form_add_question3);
        } else {
            $this->assignRef('app_form_add_question3', $app_form_add_question3);
        }

        //Hide application form on request?
        $hide_on_request = $app_form_show = $result->data->prihlaska;
        if ($hide_on_request == "1") {
            $this->assignRef('app_form_show', $app_form_show);
        }
        else {
            $value = 0;
            $this->assignRef('app_form_show', $value);
        }


        parent::display($tpl);
    }

}
