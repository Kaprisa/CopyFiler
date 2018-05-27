<?php

$EXCLUDES = ['node_modules', 'vendor'];

var_dump(in_array("vendor", $EXCLUDES));


$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('/home/kaprisa/sites/academy'), RecursiveIteratorIterator::CHILD_FIRST);
$r = array();
foreach ($it as $file_info) {
    $name = $file_info->getFileName();
    if ( $name === '.' || $name === '..' || in_array($name, $EXCLUDES)) {
        var_dump(in_array($name, $EXCLUDES));
        continue;
    }
    var_dump('Helho!');
    //if (array_has($EXCLUDES, $name)) var_dump($name);
    $path = $file_info->isDir()
        ? array($name => array())
        :
        array(
            [
                'name' => $name,
                'ext' => $file_info->getExtension(),
                'size' => $file_info->getSize(),
                'path' => str_replace('/home/kaprisa/sites/academy', '', $file_info->getPath()).'/'.$name
            ]
        );
    for ($depth = $it->getDepth() - 1; $depth >= 0; $depth--) {
        $path = array($it->getSubIterator($depth)->current()->getFilename() => $path);
    }
    $r = array_merge_recursive($r, $path);
}
