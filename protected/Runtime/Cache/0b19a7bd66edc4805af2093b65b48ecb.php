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
        <div class="my-panel-title">用户列表</div>
    
        <div class="col-xs-12" style="margin-bottom: 10px;">
            <a id="add-user" href="javascript:void(0);">添加用户</a>
        </div>

        <div class="panel-body">
            <table class="table table-bordered table-condensed">
                <tr>
                    <th>uid</th>
                    <th>uname</th>
                    <th>password</th>
                    <th>auth</th>
                    <th>action</th>
                </tr>
            <?php if(is_array($userList)): foreach($userList as $key=>$user): ?><tr id="tr-user-<?php echo ($user["uid"]); ?>">
                    <td><?php echo ($user["uid"]); ?></td>
                    <td><?php echo ($user["uname"]); ?></td>
                    <td><?php echo ($user["pwd"]); ?></td>
                    <td>
                        <?php if(is_array($user['deviceList'])): foreach($user['deviceList'] as $key=>$device): ?><span class="set-tag <?php if($device["auth"] == true): ?>set-tag-checked<?php endif; ?>" data-user-id="<?php echo ($user["uid"]); ?>" data-tag-id="<?php echo ($device["id"]); ?>"><?php echo ($device["name"]); ?></span><?php endforeach; endif; ?>
                    </td>
                    <td>
                        <a class="remove-user" href="javascript:void(0);" data-user-id="<?php echo ($user["uid"]); ?>">删除</a>
                    </td>
                </tr><?php endforeach; endif; ?>
            </table>
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