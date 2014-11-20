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

            
    <!-- 文件检索 -->
    <div class="panel panel-default" style="margin-top: 10px;">
        <div class="my-panel-title">文件检索</div>
        <div class="panel-body">

            <div class="col-xs-12">
                <a href="javascript:void(0);" class="set-tag-select-all" data-ctrl-id="device-search" data-callback="1">全选</a>
                <a href="javascript:void(0);" class="set-tag-select-other" data-ctrl-id="device-search" data-callback="1">反选</a>
                <a href="javascript:void(0);" class="set-tag-select-none" data-ctrl-id="device-search" data-callback="1">全不选</a>
            </div>

            <div class="col-xs-12" id="device-search">
                <?php if(is_array($tagInnerList)): foreach($tagInnerList as $key=>$tag): ?><span class="set-tag" data-tag-id="<?php echo ($tag["id"]); ?>" data-callback='1'><?php echo ($tag["name"]); ?></span><?php endforeach; endif; ?>
            </div>

            <div class="col-xs-12">
                <hr />
                <a href="javascript:void(0);" class="set-tag-select-all" data-ctrl-id="tag-search" data-callback="1">全选</a>
                <a href="javascript:void(0);" class="set-tag-select-other" data-ctrl-id="tag-search" data-callback="1">反选</a>
                <a href="javascript:void(0);" class="set-tag-select-none" data-ctrl-id="tag-search" data-callback="1">全不选</a>
            </div>

            <div class="col-xs-12" id="tag-search">
                <?php if(is_array($tagNormalList)): foreach($tagNormalList as $key=>$tag): ?><span class="set-tag" data-tag-id="<?php echo ($tag["id"]); ?>" data-callback='1'><?php echo ($tag["name"]); ?></span><?php endforeach; endif; ?>
            </div>
        </div>
    </div>

    <div class="panel panel-default" id="fileList" style="max-height: 300px; overflow-y: scroll;">
    <div class="panel-body">
        <div class="col-xs-12">
            <?php if(!empty($fileList)): ?><a href="javascript:void(0);" id="select-fileList-all">全选</a>
                <a href="javascript:void(0);" id="select-fileList-other">反选</a><?php endif; ?>
        </div>
        <table class="table table-hover table-condensed" style="margin-top: 20px; table-layout:fixed;">
            <tbody>
                <?php if(is_array($fileList)): foreach($fileList as $key=>$file): ?><tr id="fileTr-<?php echo ($file["file_id"]); ?>">
                        <td width="30">
                            <input type="checkbox" class="file-check" checked="true" data-file-id="<?php echo ($file["file_id"]); ?>" >
                        </td>
                        <td width="50">
                            <?php if(in_array(($file['ext']), explode(',',"jpg, jpeg, png, gif"))): ?><img src="/upload/<?php echo ($file["md5"]); ?>" alt="<?php echo ($file["show_name"]); ?>" style="width: 30px; height: 20px;" /><?php endif; ?>
                        </td>
                        <td class="file-name"><?php echo ($file["show_name"]); ?></td>
                        <td width="200">
                            <a href="javascript:void(0);" class="remove-file" data-file-id="<?php echo ($file["file_id"]); ?>">删除</a>
                            <a href="javascript:void(0);" class="update-file" data-file-id="<?php echo ($file["file_id"]); ?>">修改文件名</a>
                            <a href="javascript:void(0);" class="update-tag"  data-file-id="<?php echo ($file["file_id"]); ?>">修改标签</a>
                        </td>
                        <td>
                            <?php if(is_array($file["tag_list"])): foreach($file["tag_list"] as $tag_id=>$tag_name): ?><span class="label label-primary" style="margin-left: 10px;" data-tag-id="<?php echo ($tag_id); ?>"><?php echo ($tag_name); ?></span><?php endforeach; endif; ?>
                        </td>
                    </tr><?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

    <!-- 设备选择 -->
    <div class="panel panel-default">
        <div class="my-panel-title">更新设备（包括TV、全息、环幕）播放列表</div>
        <div class="panel-body">

            <div class="col-xs-12">
                <a href="javascript:void(0);" class="set-tag-select-all" data-ctrl-id="device-des">全选</a>
                <a href="javascript:void(0);" class="set-tag-select-other" data-ctrl-id="device-des">反选</a>
                <a href="javascript:void(0);" class="set-tag-select-none" data-ctrl-id="device-des">全不选</a>
            </div>

            <div class="col-xs-12" id="device-des">
                <?php if(is_array($tagInnerList)): foreach($tagInnerList as $key=>$tag): ?><span class="set-tag" data-tag-id="<?php echo ($tag["id"]); ?>"><?php echo ($tag["name"]); ?></span><?php endforeach; endif; ?>
            </div>

            <div class="col-xs-12">
                <hr />
                <button class="button my-btn-primary set-list" data-type="welcome">设置TV欢迎列表</button>
                <button class="button my-btn-primary set-list" data-type="default" style="margin-left: 20px;">设置TV默认列表</button>
                <button class="button my-btn-primary set-list" data-type="update" style="margin-left: 20px;">设置播放列表</button>
                <button class="button my-btn-primary set-num-list" data-toggle="modal" data-target="#modal" style="margin-left: 20px;">设置TV播放时间参数</button>
                <button class="button my-btn-primary set-list" data-type="temp" style="margin-left: 20px;">立刻播放</button>
            </div>

        </div>
    </div>

    <!-- 快捷操作 -->
    <div class="panel panel-default">
        <div class="my-panel-title">按主题设置TV播放列表</div>
        <div class="panel-body">

            <div class="col-xs-12" id="theme-des">
                <?php if(is_array($tagNormalList)): foreach($tagNormalList as $key=>$tag): ?><span class="set-tag" data-tag-id="<?php echo ($tag["id"]); ?>"><?php echo ($tag["name"]); ?></span><?php endforeach; endif; ?>
            </div>

            <div class="col-xs-12">
                <hr />
                <button class="button my-btn-primary set-list-by-theme" data-type="welcome">设置TV欢迎列表</button>
                <button class="button my-btn-primary set-list-by-theme" data-type="default" style="margin-left: 20px;">设置TV默认列表</button>
                <button class="button my-btn-primary set-list-by-theme" data-type="update" style="margin-left: 20px;">设置TV播放列表</button>
                <button class="button my-btn-primary set-list-by-theme" data-type="temp" style="margin-left: 20px;">立刻播放</button>
            </div>

        </div>
    </div>

    <div class="modal fade" id="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="panel panel-default">
              <div class="panel-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">自动播放时间（秒）</label>
                        <input type="email" class="form-control" id="set-autoPlayTime">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">手动到自动切换时间（秒）</label>
                        <input type="email" class="form-control" id="set-manuToAutoTime" value="300">
                    </div>
                    
                    <button id="set-num-submit" type="button" class="btn btn-primary">确定</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
              </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-file-sort" tabindex="-1">
        <div class="modal-dialog" style="width: 800px;">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" id="mv-up" class="btn btn-default btn-sm">
                        <span class="glyphicon glyphicon-arrow-up"></span> 上移
                    </button>
                    <button type="button" id="mv-down" class="btn btn-default btn-sm">
                        <span class="glyphicon glyphicon-arrow-down"></span> 下移
                    </button>
                    <span class="help-block">你可以微调播放列表的顺序（按着ctrl可以批量选择）</span>

                    <div class="panel panel-default" style="margin: 10px 0;">
                        <div class="panel-body" style="max-height: 300px; overflow-y: scroll; overflow-x: auto;">
                            <table class="table table-hover table-condensed">
                            </table>
                        </div>
                    </div>

                    <button type="button" class="btn my-btn-primary" id="set-list-submit">确定</button>
                    <button type="button" class="btn my-btn-primary" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div> 

    <div class="modal fade" id="modal-file-update-tag" tabindex="-1">
        <div class="modal-dialog" style="width: 800px;">
            <div class="modal-content">
                <div class="modal-body row">

                    <div class="col-xs-12">
                        <a href="javascript:void(0);" class="set-tag-select-all" data-ctrl-id="file-update-tag" style="margin-left: 10px;">全选</a>
                        <a href="javascript:void(0);" class="set-tag-select-other" data-ctrl-id="file-update-tag">反选</a>
                        <a href="javascript:void(0);" class="set-tag-select-none" data-ctrl-id="file-update-tag">全不选</a>
                    </div>

                    <div class="col-xs-12" id="file-update-tag">
                        <?php if(is_array($tagNormalList)): foreach($tagNormalList as $key=>$tag): ?><span class="set-tag" data-tag-id="<?php echo ($tag["id"]); ?>"><?php echo ($tag["name"]); ?></span><?php endforeach; endif; ?>
                    </div>

                    <div class="col-xs-12" id="file-update-tag" style="margin-top: 10px; padding-left: 25px;">
                        <button type="button" class="btn my-btn-primary" id="update-file-tag-submit">确定</button>
                        <button type="button" class="btn my-btn-primary" data-dismiss="modal">取消</button>
                    </div>

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
        

        
        <div class="hidden"></div>
    </body>
</html>