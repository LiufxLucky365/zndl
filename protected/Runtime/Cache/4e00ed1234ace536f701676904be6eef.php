<?php if (!defined('THINK_PATH')) exit();?><tr id="tagTr-<?php echo ($tagId); ?>">
    <td><?php echo ($tagId); ?></td>
    <td><?php echo ($tagName); ?></td>
    <td>
        <a href="javascript:void(0);" class="remove-tag" data-tag-id="<?php echo ($tagId); ?>">remove</a>
    </td>
</tr>