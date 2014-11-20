<?php
/**
 * 自动添加当前模块的静态资源
 * PUBLIC_PATH/Js[Css]/MODULE_NAME/common.js[css]
 * PUBLIC_PATH/Js[Css]/MODULE_NAME/ACTION_NAME.js[css]
 */
class AutoLoadResBehavior extends Behavior{
    // 行为扩展的执行入口必须是run
    public function run(&$params){
        $root = PUBLIC_PATH.'/'.MODULE_NAME;
        $css_files = array();
        $js_files  = array();
        if (file_exists(PUBLIC_PATH.'/Common/common.css')){
            array_push($css_files, PUBLIC_PATH.'/Common/common.css');
        }
        if (file_exists(PUBLIC_PATH.'/Common/common.js')){
            array_push($js_files, PUBLIC_PATH.'/Common/common.js');
        }
        foreach(array('common', ACTION_NAME) as $s) {
            if (file_exists("$root/$s.css")){
                array_push($css_files, "$root/$s.css");
            }
            if (file_exists("$root/$s.js")){
                array_push($js_files, "$root/$s.js");
            }
        }
        $css_files = str_replace(PUBLIC_PATH, '__PUBLIC__', $css_files);
        $js_files = str_replace(PUBLIC_PATH, '__PUBLIC__', $js_files);

        $params->assign('css_files', $css_files);
        $params->assign('js_files', $js_files);
    }
}