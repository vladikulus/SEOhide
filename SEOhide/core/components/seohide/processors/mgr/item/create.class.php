<?php

class SEOhideItemCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'SEOhideItem';
    public $classKey = 'SEOhideItem';
    public $languageTopics = array('seohide');
    //public $permission = 'create';


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $name = trim($this->getProperty('resource_id'));
        if (empty($name)) {
            $this->modx->error->addField('resource_id', $this->modx->lexicon('seohide_item_err_name'));
        } elseif ($this->modx->getCount($this->classKey, array('name' => $name))) {
            $this->modx->error->addField('resource_id', $this->modx->lexicon('seohide_item_err_ae'));
        }

        return parent::beforeSet();
    }

}

return 'SEOhideItemCreateProcessor';