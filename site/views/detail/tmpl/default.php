<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

//top backlink
if (isset($this->top_backlink)) {

    echo '<div class="com_bis_backlink com_bis_backlink_top">
    <a href="' . $this->top_backlink . '">' . JText::_('COM_BIS_BACK') . '</a>
      </div>';
}

echo '<div class="com_bis_detail">';

//Main data
echo $this->result;

echo '</div>';


//Application form
//Allowed by BIS
if ($this->app_form_show == "1") {

    //Alloowed by param
    if ($this->show_appl_form) {

        if ($this->app_form_message == 'ok') {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_BIS_APPL_FORM_SUCCESS'));
        }
        if ($this->app_form_message == 'error') {
            JError::raiseWarning(100, JText::_('COM_BIS_APPL_FORM_ERR'));
        }
        ?>
        <script type="text/javascript">
            window.addEvent('domready', function() {

                // The elements used.
                var ApplForm = document.id('ApplForm');

                // Validation.
                new Form.Validator.Inline(ApplForm);

            });
        </script>

        <form id="ApplForm" method="post">
            <h2><?php echo JText::_('COM_BIS_APPL_FORM'); ?></h1>
                <fieldset>
                    <table>
                        <tr>
                            <td><label for="givenname"><?php echo JText::_('COM_BIS_APPL_FORM_GNAME'); ?></label></td>
                            <td><input type="text" id="givenname" name="givenname" class="required"/></td>
                        </tr>
                        <tr>
                            <td><label for="surname"><?php echo JText::_('COM_BIS_APPL_FORM_SNAME'); ?></label></td>
                            <td><input type="text" id="surname" name="surname" class="required"/></td>
                        </tr>
                        <tr>
                            <td><label for="birthdate"><?php echo JText::_('COM_BIS_APPL_FORM_BIRTH'); ?></label></td>
                            <td><input type="text" data-validators="validate-date dateFormat:'%d.%m.%Y'" id="birthdate" name="birthdate" class="required"/></td>
                        </tr>
                        <tr>
                            <td><label for="phonenumber"><?php echo JText::_('COM_BIS_APPL_FORM_PHONE'); ?></label></td>
                            <td><input type="text" id="phonenumber" name="phonenumber" class="required validate-numeric"/></td>
                        </tr>
                        <tr>
                            <td><label for="eladdr"><?php echo JText::_('COM_BIS_APPL_FORM_EMAIL'); ?></label></td>
                            <td><input type="text" id="eladdr" name="eladdr" class="required validate-email"/></td>
                        </tr>
                        <?php if (strlen($this->app_form_add_question) > 0) { ?>
                            <tr>
                                <td><label for="ad_info"><?php echo $this->app_form_add_question; ?></label></td>
                                <td>
                                  <textarea id="ad_info" name="ad_info" class="required" rows="3" cols="30"></textarea>
                                  <input type="hidden" name="ad_title" id="ad_title" value="<?php echo $this->app_form_add_question; ?>" />
                                </td>
                            </tr
                        <?php } ?>
                        <?php if (strlen($this->app_form_add_question2) > 0) { ?>
                            <tr>
                                <td><label for="ad_info2"><?php echo $this->app_form_add_question2; ?></label></td>
                                <td>
                                  <textarea id="ad_info2" name="ad_info2" class="required" rows="3" cols="30"></textarea>
                                  <input type="hidden" name="ad_title2" id="ad_title2" value="<?php echo $this->app_form_add_question2; ?>" />
                                </td>
                            </tr
                        <?php } ?>
                        <?php if (strlen($this->app_form_add_question3) > 0) { ?>
                            <tr>
                                <td><label for="ad_info3"><?php echo $this->app_form_add_question3; ?></label></td>
                                <td>
                                  <textarea id="ad_info3" name="ad_info3" class="required" rows="3" cols="30"></textarea>
                                  <input type="hidden" name="ad_title3" id="ad_title3" value="<?php echo $this->app_form_add_question3; ?>" />
                                </td>
                            </tr
                        <?php } ?>
                        <tr>
                            <td><label for="comment"><?php echo JText::_('COM_BIS_APPL_FORM_COMMENT'); ?></label></td>
                            <td><textarea id="comment" name="comment" rows="5" cols="30"></textarea></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input type="submit" value="<?php echo JText::_('COM_BIS_APPL_FORM_SEND'); ?>" />

                                <input type="text" id="email" name="email"
                                       style="display: none; visibility: hidden;" />

                                <input type="hidden" id="form-action-id" name="form-action-id"
                                       value="<?php echo $this->params->get('bis_id'); ?>" />

                                <input type="hidden" id="app-form-send" name="app-form-sent"
                                       value="true"  />

                                <input type="hidden" id="org-email" name="org-email"
                                       value="<?php echo $this->org_email; ?>"  />

                                <input type="hidden" id="event-name" name="event-name"
                                       value="<?php echo $this->event_name; ?>"  />

                            </td>
                        </tr>
                    </table>
                </fieldset>
        </form>
        <?php
    }
}
//bottom backlink
if (isset($this->bottom_backlink)) {
    echo '<div class="com_bis_backlink com_bis_backlink_bottom">
    <a href="' . $this->bottom_backlink . '">' . JText::_('COM_BIS_BACK') . '</a>
      </div>';
}
