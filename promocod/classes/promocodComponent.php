<?php
class promocodComponent extends classes\Component\Component{
    
   public function show($model, $item) {
        $link = $this->LoadResource('html', 'html')->getLink("usuario/promocod/aderir/{$item['cod']}").
                "&&utm_source=promo&utm_medium=promo_{$item['cod']}&utm_campaign={$item['cod']}";
        echo "<div class='col-xs-12'>
            <h3>Url da promoção</h3>
            <textarea class='form-control' cols='50' rows='3' style='resize: none;' readonly=''>$link</textarea>
            </div>";
        
        parent::show($model, $item);
    }
    
}
