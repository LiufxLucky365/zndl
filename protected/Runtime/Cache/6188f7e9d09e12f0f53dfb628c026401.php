<?php if (!defined('THINK_PATH')) exit();?><div class="panel panel-default" id="fileList" style="max-height: 300px; overflow-y: scroll;">
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