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
            'resource.uri'
        ));
        $query->where(array(
            'SEOhideItem.active:!=' => 0
        ));
        $query->prepare();
        $query->stmt->execute();
        $aliasArray = $query->stmt->fetchAll(PDO::FETCH_ASSOC);

        $convertedAliasArray = array();
        if(!count($aliasArray)){
            break;
        } else {
            foreach($aliasArray as $a){
                $convertedAliasArray[] = $a["uri"];
            }
        }

        if(!$contentTypeObj = $modx->getObject("modContentType", ["name" => "HTML"])){break;}
        $file_extensions = $contentTypeObj->get("file_extensions");
        /**/

        $doc = new DOMDocument();

        libxml_use_internal_errors(true);
        $source = mb_convert_encoding($modx->resource->_output, 'HTML-ENTITIES', 'utf-8');

        @$doc->loadHTML($source);
        $links = $doc->getElementsByTagName('a');

        $linksArray = [];
        $modx->log(1, print_r($aliasArray, 1));
        foreach ($links as $link) {
            $modx->log(1, print_r($link->getAttribute('href'), 1));

            if(in_array($link->getAttribute('href'), $convertedAliasArray)){
                $hashLink = $doc->createElement("a", $link->nodeValue);

                $hashHref = base64_encode($link->getAttribute('href'));
                $hashstring = base64_encode($hashHref);
                $hashLink->setAttribute('hashstring', $hashstring);
                $hashLink->setAttribute('hashtype', 'href');
                $hashLink->setAttribute('href', '');
                $link->parentNode->replaceChild($hashLink, $link);

                $linksArray[$hashstring] = $hashHref;
            }

        }
        $script = $doc->createElement("script", "var seoHrefs = " . json_encode($linksArray));
        $script->setAttribute('type', "text/javascript");
        $doc->appendChild($script);

        //check content-type meta
        $metas = $doc->getElementsByTagName('meta');

        $charset = false;
        foreach($metas as $meta){
            if($meta->getAttribute('http-equiv') == 'Content-Type' && $meta->getAttribute('content') == 'text/html; charset=utf-8'){
                $charset = true;
            }
        }

        if(!$charset){
            $head = $doc->getElementsByTagName('head')->item(0);
            if($head){
                $meta = $doc->createElement("meta", "");
                $meta->setAttribute('http-equiv', 'Content-Type');
                $meta->setAttribute('content', 'text/html; charset=utf-8');
                $head->appendChild($meta);
            }

        }

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