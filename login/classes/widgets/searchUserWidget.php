<?php

class searchUserWidget extends \classes\Component\widget{   
    protected $title = "Pesquisar Usuários";
    public function widget() {
        $this->openPanel();
            $url = $this->Html->getLink(CURRENT_PAGE, true,true);
            echo "<form method='get' action='$url'>";
            echo    "<input type='hidden' value='".CURRENT_PAGE."' name='url'/>";
            echo    "<input type='text' class='search form-control' placeholder='Pesquisar usuários' name='user_search' id='user_search'/>";
            echo "</form>";
        $this->closeWidget();
    }
}