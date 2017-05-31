<?php
/**
 * Created by Valeriy Kirichenko
 * Date: 13.08.13
 * Time: 14:22
 */

//print '<pre>';

function getElements($path = '.', &$list)
{
    $d = dir($path);

    while (false !== ($entry = $d->read())) {
        if ($entry == '.' or $entry == '..')
            continue;

        if (is_dir($path . '/' . $entry)) {
            getElements($path . '/' . $entry, $list);
        }

        if (preg_match('/(.*)\.(php|html|tpl)/i', $entry, $out)) {
            $list[] = array(
                'name' => $out[1],
                'path' => $path . '/' . $entry
            );
        }
//        echo $path . '/' . $entry . "\n";
    }
    $d->close();
}

getElements('assets/chunks', $chunks);
getElements('assets/templates', $templates);

foreach ($chunks as $item) {
    $chunk = $modx->getObject('modChunk', array('static_file' => $item['path']));

    if (!$chunk) {
        $chunk = $modx->newObject('modChunk');
        $chunk->set('name', $item['name']);
        $chunk->set('source', 1);
        $chunk->set('static', 1);
        $chunk->set('properties', 'a:0:{}');
        $chunk->set('static_file', $item['path']);
        $chunk->set('description', '');
//        $chunk->setContent('');
        $chunk->save();
    }
}


foreach ($templates as $item) {
    $template = $modx->getObject('modTemplate', array('static_file' => $item['path']));

    if (!$template) {
        $template = $modx->newObject('modTemplate');
        $template->set('templatename', $item['name']);
        $template->set('source', 1);
        $template->set('static', 1);
        $template->set('properties', 'a:0:{}');
        $template->set('static_file', $item['path']);
        $template->set('description', '');
//        $template->setContent('');
        $template->save();
    }
}