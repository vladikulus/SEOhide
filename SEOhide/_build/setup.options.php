<?php
/** @var array $options */

$exists = $chunks = false;
$output = null;
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
        //$exists = $modx->getObject('transport.modTransportPackage', array('package_name' => 'pdoTools'));
        break;

    case xPDOTransport::ACTION_UPGRADE:
        //$exists = $modx->getObject('transport.modTransportPackage', array('package_name' => 'pdoTools'));
        if (!empty($options['attributes']['chunks'])) {
            $chunks = '<ul id="formCheckboxes" style="height:200px;overflow:auto;">';
            foreach ($options['attributes']['chunks'] as $k => $v) {
                $chunks .= '
                <li>
                    <label>
                        <input type="checkbox" name="update_chunks[]" value="' . $k . '"> ' . $k . '
                    </label>
                </li>';
            }
            $chunks .= '</ul>';
        }
        break;

    case xPDOTransport::ACTION_UNINSTALL:
        break;
}

$output = '';

return $output;