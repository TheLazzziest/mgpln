<?php

namespace Megaforms\Vendor\Libs\View;


abstract class BaseTemplater implements TemplaterInterface{


    private $_asset;

    public function __construct(AssetManager $manager){
        $this->_asset;
    }

    private function renderHeader(){
        $this->_asset->register_js();
        $this->_asset->register_css();
    }

    private function renderFooter(){}

    public function render($file, array $params = [])
    {
        $content = $this->renderHeader();
        $content = $this->renderFooter();
    }

}
?>
