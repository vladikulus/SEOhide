<?php

class SEOhideItemRemoveProcessor extends modObjectProcessor
{
    public $objectType = 'SEOhideItem';
    public $classKey = 'SEOhideItem';
    public $languageTopics = array('seohide');
    //public $permission = 'remove';


    /**
     * @return array|string
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        $ids = $this->modx->fromJSON($this->getProperty('ids'));
        if (empty($ids)) {
            return $this->failure($this->modx->lexicon('seohide_item_err_ns'));
        }

        foreach ($ids as $id) {
            /** @var SEOhideItem $object */
            if (!$object = $this->modx->getObject($this->classKey, $id)) {
                return $this->failure($this->modx->lexicon('seohide_item_err_nf'));
            }

            $object->remove();
        }

        return $this->success();
    }

}

return 'SEOhideItemRemoveProcessor';