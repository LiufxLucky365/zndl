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
            <li role="presentation" <?php if($_menu == 'Index'): ?>class="active"<?php endif; ?>><a href="/index.php/Index/">文件上传</a></li>
            <li role="presentation" <?php if($_menu == 'File'): ?>class="active"<?php endif; ?>><a href="/index.php/File/">文件列表</a></li>
            <li role="presentation" <?php if($_menu == 'Tag'): ?>class="active"<?php endif; ?>><a href="/index.php/Tag/">标签管理</a></li>
        </ul>
    </div>
</div>

        <div class="container" style="padding-bottom:45px">

            
    <table>
        <tr>
            <td>
                <select name="" id="fileListSrc" class="form-control" multiple="multiple" style="height: 400px; width: 200px;">
                    <?php if(is_array($fileList)): foreach($fileList as $key=>$file): ?><option value="<?php echo ($file["id"]); ?>"><?php echo ($file["show_name"]); ?></option><?php endforeach; endif; ?>
                </select>
            </td>
            <td>
                <a href="javascript:void(0);" id="mv-right">←</a>
                <a href="javascript:void(0);" id="mv-left">→</a>
                <a href="javascript:void(0);" id="mv-up">↑</a>
                <a href="javascript:void(0);" id="mv-down">↓</a>
            </td>
            <td>
                <select name="" id="fileListDes" class="form-control" multiple="multiple" style="height: 400px; width: 200px;">
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <button class="button my-btn-primary" id="submit-sort"  style="margin-top: 20px;">确定</button>
            </td>
        </tr>
    </table>

            
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