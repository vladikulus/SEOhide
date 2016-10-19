<?php
/** @var modX $modx */
switch ($modx->event->name) {
    case "OnWebPagePrerender":

        $modx->addPackage('seohide', $modx->getOption('core_path').'components/seohide/model/');
        $query = $modx->newQuery('SEOhideItem');
        $query->innerJoin('modResource', 'resource', 'resource.id = SEOhideItem.resource_id');
        $query->select(array(
            'resource.alias',
            'resource.isfolder'
        ));
        $query->where(array(
            'SEOhideItem.active:!=' => 0
        ));
        $query->prepare();
        $query->stmt->execute();
        $aliasArray = $query->stmt->fetchAll(PDO::FETCH_ASSOC);
        if(!count($aliasArray)){
            break;
        }

        if(!$contentTypeObj = $modx->getObject("modContentType", ["name" => "HTML"])){break;}
        $file_extensions = $contentTypeObj->get("file_extensions");

        foreach($aliasArray as $key => $aliasArrayRow){
            if($aliasArrayRow["isfolder"] == 0){
                $aliasArray[$key] = $aliasArrayRow["alias"] . "/";
            } else {
                $aliasArray[$key] = $aliasArrayRow["alias"] . $file_extensions;
            }

        }

        /**/

        $doc = new DOMDocument();

        libxml_use_internal_errors(true);
        $source = mb_convert_encoding($modx->resource->_output, 'HTML-ENTITIES', 'utf-8');

        @$doc->loadHTML($source);
        $links = $doc->getElementsByTagName('a');

        $linksArray = [];
        foreach ($links as $link) {

            if(in_array($link->getAttribute('href'), $aliasArray)){
                $hashLink = $doc->createElement("a", $link->nodeValue);

                $hashHref = base64_encode($link->getAttribute('href'));
                $hashstring = base64_encode($hashHref);
                $hashLink->setAttribute('hashstring', $hashstring);
                $hashLink->setAttribute('hashtype', 'href');
                $hashLink->setAttribute('href', '#');
                $link->parentNode->replaceChild($hashLink, $link);

                $linksArray[$hashstring] = $hashHref;
            }

        }
        $script = $doc->createElement("script", "var seoHrefs = " . json_encode($linksArray));
        $script->setAttribute('type', "text/javascript");
        $doc->appendChild($script);


        $modx->resource->_output = $doc->saveHTML();
        break;

    case "OnLoadWebDocument":
        $modx->regClientScript('assets/components/seohide/js/web/lib/BASE64.js');
        $modx->regClientScript('assets/components/seohide/js/web/default.js');
        break;
}