<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
    <head>
        
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <title><?php echo L('APP_TITLE');?></title>

        <link rel="Shortcut Icon" href="__PUBLIC__/img/minilogo.jpg" />
        <link href="__PUBLIC__/Common/theme.css" rel="stylesheet">
        <link href="__PUBLIC__/Lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="__PUBLIC__/Lib/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
        <?php if(is_array($css_files)): $i = 0; $__LIST__ = $css_files;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><link href="<?php echo ($vo); ?>?v=<?php echo C('VERSION');?>" rel="stylesheet"><?php endforeach; endif; else: echo "" ;endif; ?>
        
        
    </head>

    <body>
        <div class="col-xs-12">
    <div class="page-header">
        <h1>中南电力展厅控制系统<small>后台管理</small></h1>

        <ul class="nav nav-pills" role="tablist">
            <li role="presentation" <?php if($menu_active == 'Index/index'): ?>class="active"<?php endif; ?>><a href="/index.php/Index">文件上传</a></li>
            <li role="presentation" <?php if($menu_active == 'File/index'): ?>class="active"<?php endif; ?>><a href="/index.php/File/index/fetch/1">文件列表</a></li>
            <li role="presentation" <?php if($menu_active == 'Tag/index'): ?>class="active"<?php endif; ?>><a href="/index.php/Tag">标签管理</a></li>
            <li role="presentation" <?php if($menu_active == 'File/sortIndex'): ?>class="active"<?php endif; ?>><a href="/index.php/File/sortIndex">文件默认顺序管理</a></li>
            <li role="presentation" <?php if($menu_active == 'User/index'): ?>class="active"<?php endif; ?>><a href="/index.php/User">账号管理</a></li>

        <?php if($_SESSION['role']== 'admin'): ?><li role="presentation" <?php if($menu_active == 'User/adminIndex'): ?>class="active"<?php endif; ?>><a href="/index.php/User/adminIndex">用户管理</a></li><?php endif; ?>
        </ul>
    </div>
</div>

        <div class="container" style="padding-bottom:45px">

            
    <div class="panel panel-default" style="margin-top: 10px;">
        <div class="my-panel-title">设置文件所属设备与标签分类</div>
        <div class="panel-body">

            <div class="col-xs-12">
                <a href="javascript:void(0);" class="set-tag-select-all" data-ctrl-id="device-set">全选</a>
                <a href="javascript:void(0);" class="set-tag-select-other" data-ctrl-id="device-set">反选</a>
                <a href="javascript:void(0);" class="set-tag-select-none" data-ctrl-id="device-set">全不选</a>
            </div>

            <div class="col-xs-12" id="device-set">
                <?php if(is_array($tagInnerList)): foreach($tagInnerList as $key=>$tag): ?><span class="set-tag" data-tag-id="<?php echo ($tag["id"]); ?>"><?php echo ($tag["name"]); ?></span><?php endforeach; endif; ?>
            </div>

            <div class="col-xs-12">
                <hr />
                <a href="javascript:void(0);" class="set-tag-select-all" data-ctrl-id="tag-set">全选</a>
                <a href="javascript:void(0);" class="set-tag-select-other" data-ctrl-id="tag-set">反选</a>
                <a href="javascript:void(0);" class="set-tag-select-none" data-ctrl-id="tag-set">全不选</a>
                |
                <a href="javascript:void(0);" id="add-tag">添加标签</a>
            </div>

            <div class="col-xs-12" id="tag-set">
                <?php if(is_array($tagNormalList)): foreach($tagNormalList as $key=>$tag): ?><span class="set-tag" data-tag-id="<?php echo ($tag["id"]); ?>"><?php echo ($tag["name"]); ?></span><?php endforeach; endif; ?>
            </div>

            <div class="col-xs-12">
                <hr />
                <button href="javascript:void(0)" id="submit" class="button my-btn-primary">提交</button>
                <input type="file" name="file" id="file_upload" />
            </div>

            <div class="col-xs-12">
                <div class="well well-sm">
                    <ul id="upload-result">
                    </ul>
                </div>
            </div>

        </div>
    </div>




            
        </div>
        
        
        
        <div class="col-xs-12 text-center" style="margin: 20px auto; color: #959595;">
    <div style="border-top: 1px solid #eee; padding-top: 20px;">
        Copyright &copy; 2015 Actl(CCNU) Limited. All Right Reserved
    </div>
</div>

        
        <script type="text/javascript" src="__PUBLIC__/Lib/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="__PUBLIC__/Lib/jquery.placeholder.js"></script>
        <script>
        $(function(){
            $('input, textarea').placeholder();
        });
        </script>
        <script type="text/javascript" src="__LIB__/bootstrap/js/bootstrap.min.js"></script>
        <?php if(is_array($js_files)): foreach($js_files as $key=>$vo): ?><script type="text/javascript" src="<?php echo ($vo); ?>?v=<?php echo C('VERSION');?>"></script><?php endforeach; endif; ?>
        
    <script src="__LIB__/uploadify/jquery.uploadify.min.js"></script>


        
        <div class="hidden"></div>
    </body>
</html>