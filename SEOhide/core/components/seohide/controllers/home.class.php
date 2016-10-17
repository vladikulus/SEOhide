<?php

/**
 * The home manager controller for SEOhide.
 *
 */
class SEOhideHomeManagerController extends modExtraManagerController
{
    /** @var SEOhide $SEOhide */
    public $SEOhide;


    /**
     *
     */
    public function initialize()
    {
        $path = $this->modx->getOption('seohide_core_path', null,
                $this->modx->getOption('core_path') . 'components/seohide/') . 'model/seohide/';
        $this->SEOhide = $this->modx->getService('seohide', 'SEOhide', $path);
        parent::initialize();
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return array('seohide:default');
    }


    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return true;
    }


    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('seohide');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addCss($this->SEOhide->config['cssUrl'] . 'mgr/main.css');
        $this->addCss($this->SEOhide->config['cssUrl'] . 'mgr/bootstrap.buttons.css');
        $this->addJavascript($this->SEOhide->config['jsUrl'] . 'mgr/seohide.js');
        $this->addJavascript($this->SEOhide->config['jsUrl'] . 'mgr/misc/utils.js');
        $this->addJavascript($this->SEOhide->config['jsUrl'] . 'mgr/misc/combo.js');
        $this->addJavascript($this->SEOhide->config['jsUrl'] . 'mgr/widgets/items.grid.js');
        $this->addJavascript($this->SEOhide->config['jsUrl'] . 'mgr/widgets/items.windows.js');
        $this->addJavascript($this->SEOhide->config['jsUrl'] . 'mgr/widgets/home.panel.js');
        $this->addJavascript($this->SEOhide->config['jsUrl'] . 'mgr/sections/home.js');

        $this->addHtml('<script type="text/javascript">
        SEOhide.config = ' . json_encode($this->SEOhide->config) . ';
        SEOhide.config.connector_url = "' . $this->SEOhide->config['connectorUrl'] . '";
        Ext.onReady(function() {
            MODx.load({ xtype: "seohide-page-home"});
        });
        </script>
        ');
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        return $this->SEOhide->config['templatesPath'] . 'home.tpl';
    }
}