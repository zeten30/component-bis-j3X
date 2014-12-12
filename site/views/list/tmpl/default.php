<?php

// No direct access 
defined('_JEXEC') or die('Restricted access');

//Tabbed view
if (isset($this->tabMenu)) {
    echo $this->tabMenu;
    echo '<div class="com_bis_list com_bis_tablist">';
} else {
    echo '<div class="com_bis_list">';
}

if ($this->mod_bis_filter_used) {
    echo "<p class=\"mod_bis_filter_is_used\">
    " . JText::_('COM_BIS_MOD_BIS_FILTER_IS_USED') . "
    </p>";
}

echo $this->result;

echo '</div>';
