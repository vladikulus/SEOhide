<?php
/** @var modX $modx */
switch ($modx->event->name) {
    case "OnWebPagePrerender":
        if($disabled_pages = $modx->getOption('seohide_disabled_pages')){
            $disabled_pagesArray = explode(",", $disabled_pages);
            if(in_array($modx->resource->id, $disabled_pagesArray)) {
                break;
            }
        }


        $modx->addPackage('seohide', $modx->getOption('core_path').'components/seohide/model/');
        $query = $modx->newQuery('SEOhideItem');
        $query->where(["active" => "1"]);
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
                $aliasArray[$key] = $aliasArrayRow["alias"] . $file_extensions;
            } else {
                $aliasArray[$key] = $aliasArrayRow["alias"] . "/";
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
    case "OnHandleRequest":
        $bots = array(
            'rambler','googlebot','aport','yahoo','msnbot','turtle','mail.ru','omsktele',
            'yetibot','picsearch','sape.bot','sape_context','gigabot','snapbot','alexa.com',
            'megadownload.net','askpeter.info','igde.ru','ask.com','qwartabot','yanga.co.uk',
            'scoutjet','similarpages','oozbot','shrinktheweb.com','aboutusbot','followsite.com',
            'dataparksearch','google-sitemaps','appEngine-google','feedfetcher-google',
            'liveinternet.ru','xml-sitemaps.com','agama','metadatalabs.com','h1.hrn.ru',
            'googlealert.com','seo-rus.com','yaDirectBot','yandeG','yandex',
            'yandexSomething','Copyscape.com','AdsBot-Google','domaintools.com',
            'Nigma.ru','bing.com','dotnetdotcom'
        );
        foreach($bots as $bot) {
            if(stripos($_SERVER['HTTP_USER_AGENT'], $bot) !== false){
                $botname = $bot;
                $modx->sendErrorPage();
            }
        }
}