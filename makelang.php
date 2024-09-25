#!/usr/bin/env php
<?php

define("DS", DIRECTORY_SEPARATOR);

define("BASEPATH", dirname(__FILE__) .DS);

$languages = array(
    'cs',
    'de',
    'en',
    'es',
    'fr',
    'it',
    'nl',
    'pl',
    'ro',
    'ru',
);

$workPaths = array(
    'npds',
    'app'
);

//
function phpGrep($q, $path) {
    $ret = array();

    $fp = opendir($path);

    while($f = readdir($fp)) {
        if( preg_match("#^\.+$#", $f) ) continue; // ignore symbolic links

        $file_full_path = $path.DS.$f;

        if(is_dir($file_full_path)) {
            $ret = array_unique(array_merge($ret, phpGrep($q, $file_full_path)));
        }
        else if(stristr(file_get_contents($file_full_path), $q)) {
            $ret[] = $file_full_path;
        }
    }

    return $ret;
}

//
if(is_dir(BASEPATH .'app'.DS.'Packages')) {
    $path = str_replace('/', DS, BASEPATH .'app/Packages/*');

    $dirs = glob($path , GLOB_ONLYDIR);

    foreach($dirs as $package) {
        $workPaths[] = str_replace('/', DS, 'app/Packages/'.basename($package));
    }
}

if(is_dir(BASEPATH .'app'.DS.'Modules')) {
    $path = str_replace('/', DS, BASEPATH .'app/Modules/*');

    $dirs = glob($path , GLOB_ONLYDIR);

    foreach($dirs as $module) {
        $workPaths[] = str_replace('/', DS, 'app/Modules/'.basename($module));
    }
}

if(is_dir(BASEPATH .'app'.DS.'Themes')) {
    $path = str_replace('/', DS, BASEPATH .'app/Themes/*');

    $dirs = glob($path , GLOB_ONLYDIR);

    foreach($dirs as $theme) {
        $workPaths[] = str_replace('/', DS, 'app/Themes/'.basename($theme));
    }
}

//
$options = getopt('', array('path::'));

if(! empty($options['path'])) {
    $worksPaths = array_map('trim', explode(',', $options['path']));
}

foreach($workPaths as $workPath) {
    if(! is_dir(BASEPATH .$workPath)) {
        continue;
    }

    $start = ($workPath == 'app') ? "__('" : "__d('";

    $results = phpGrep($start, BASEPATH .$workPath);

    if(empty($results)) {
        continue;
    }

    if($workPath == 'app') {
        $pattern = '#__\(\'(.*)\'(?:,.*)?\)#smU';
    }
    else {
        $pattern = '#__d\(\'(?:.*)?\',.?\s?\'(.*)\'(?:,.*)?\)#smU';
    }

    echo "Using PATERN: '" .$pattern."'\n";

    $messages = array();

    foreach($results as $key => $filePath) {
        $file = substr($filePath, strlen(BASEPATH));

        if($workPath == 'app') {
            $testPath = substr($filePath, strlen(BASEPATH));

            if(str_starts_with($testPath, 'app/Modules') || str_starts_with($testPath, 'app/Themes')) {
                continue;
            }
        }

        $content = file_get_contents($filePath);

        if(preg_match_all($pattern, $content, $matches)) {
            foreach($matches[1] as $message) {
                //$message = trim($message);

                if($message == '$msg, $args = null') {
                    // This is the function
                    continue;
                }

                $messages[] = str_replace("\\'", "'", $message);
            }
        }
    }

    if(!empty($messages)) {
        echo 'Messages found on path "'.$workPath.'". Processing...'.PHP_EOL;

        $messages = array_flip($messages);

        foreach($languages as $language) {
            $langFile = BASEPATH .$workPath.'/Language/'.$language.'/messages.php';

            if(is_readable($langFile)) {
                $oldData = include($langFile);

                $oldData = is_array($oldData) ? $oldData : array();
            }
            else {
                $oldData = array();
            }

            foreach($messages as $message => $value) {
                if(array_key_exists($message, $oldData)) {
                    $value = $oldData[$message];

                    if(!empty($value) && is_string($value)) {
                        $messages[$message] = $value;
                    }
                    else {
                        $messages[$message] = '';
                    }
                }
                else {
                    $messages[$message] = '';
                }
            }

            ksort($messages);

            $output = "<?php

return " .var_export($messages, true).";\n";

            //$output = preg_replace("/^ {2}(.*)$/m","    $1", $output);

            file_put_contents($langFile, $output);

            echo 'Written the Language file: "'.str_replace(BASEPATH, '', $langFile).'"'.PHP_EOL;
        }
    }

    echo PHP_EOL;
}
