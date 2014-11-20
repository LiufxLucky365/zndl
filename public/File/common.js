$(function() {
    /**
     * 辅助操作类
     */
    $('body').on('click', '#fileList tr', function() {
        var self = $(this)
        var trClick = self.find('.file-check')[0]
        trClick.checked = !trClick.checked
    })
    $('body').on('click', "#fileList tr input[type='checkbox']", function(event) {
        event.stopPropagation()
    })
    // 检索文件，更新文件列表
    function deviceSearch() {
        var self = $(this)
        var deviceIdList = []
        var tagIdList = []

        $('#device-search .set-tag.set-tag-checked').each(function(i, e) {
            deviceIdList.push($(e).attr('data-tag-id'))
        })
        $('#tag-search .set-tag.set-tag-checked').each(function(i, e) {
            tagIdList.push($(e).attr('data-tag-id'))
        })

        var data = {
            fetch: 1, // 需要提前渲染
            deviceIdList: deviceIdList,
            tagIdList: tagIdList,
        }

        $.post('/index.php/File/getFileList', data, function(back) {
            if (back.status == 1) {
                $('#fileList').replaceWith(back.data)
            } else {
                alert(back.info)
            }
        })
    }

    /**
     * 更新config文件类
     */
    $('.set-list').click(function() {
        var self = $(this)
        var type = self.attr('data-type') // setTempList setPlayList setDefaultList
        var $modal = $('#modal-file-sort')
        var $modalTab = $modal.find('table')

        var $fileList = $('#fileList tr:has(.file-check:checked)')
        var $fileListClone = $fileList.clone()
        $fileListClone.find('a').remove() // 移除操作连接，防止用户迷惑
        $modalTab.html($fileListClone)
        $modal.modal('show')
        $('#set-list-submit').attr('data-type', type)
    })
    /* 设置播放参数 */
    $('#set-num-submit').click(function() {
        var deviceIdList = []
        var $autoPlayTime = $("#set-autoPlayTime")
        var $manuToAutoTime = $("#set-manuToAutoTime")
        var autoPlayTime = $autoPlayTime.val()
        var manuToAutoTime = $manuToAutoTime.val()
        $('#device-des .set-tag.set-tag-checked').each(function(i, e) {
            deviceIdList.push($(e).attr('data-tag-id'))
        })

        if (autoPlayTime != '' && manuToAutoTime != '') {
            if (deviceIdList.length > 0) {
                var post = {
                    deviceIdList: deviceIdList,
                    autoPlayTime: autoPlayTime,
                    manuToAutoTime: manuToAutoTime,
                }
                $.post('/index.php/File/setTvPlayTime', post, function(back) {
                    alert(back.info)
                    $("#modal").modal('hide')
                    $autoPlayTime.val('')
                    $manuToAutoTime.val($manuToAutoTime[0].defaultValue)
                })
            } else {
                alert('未选择设备')
            }
        } else {
            alert('选项未填完整')
        }
    })

    /**
     * 文件排序相关
     */
    $('body').on('click', '#modal-file-sort table tr', function(event) {
        var self = $(this)

        if (event.ctrlKey) {
            self.addClass('info')
            // 两.info之间的tr也选择
            var $checkList = $("#modal-file-sort table tr.info")
            var start = $($checkList[0]).index()
            var end = $($checkList[$checkList.length - 1]).index()
            $("#modal-file-sort table tr").each(function(i, e) {
                if (i >= start && i <= end) {
                    $(e).addClass('info')
                }
            })
            return
        }
        self.addClass('info').siblings().removeClass('info')
    })
    $('#mv-up').click(function() {
        var $fileList = $('#modal-file-sort table tr.info')
        if ($fileList.length > 0) {
            var $prev = $($fileList[0]).prev()
            if ($prev.length > 0) {
                $fileList.insertBefore($prev)
            } else {
                alert('已经是最前')
            }
        } else {
            alert('未选择行')
        }
    })
    $('#mv-down').click(function() {
        var $fileList = $('#modal-file-sort table tr.info')
        if ($fileList.length > 0) {
            var $end = $($fileList[$fileList.length - 1]).next()
            if ($end.length > 0) {
                $fileList.insertAfter($end)
            } else {
                alert('已经是最后')
            }
        } else {
            alert('未选择行')
        }
    })
    $('#set-list-submit').click(function() {
        var self = $(this)
        var type = self.attr('data-type')
        var $modal = $('#modal-file-sort')

        // 获取所要设置的设备id列表
        var deviceIdList = []
        $('#device-des .set-tag.set-tag-checked').each(function(i, e) {
            deviceIdList.push($(e).attr('data-tag-id'))
        })
        if (deviceIdList.length == 0) {
            alert('未选择设备')
            $modal.modal('hide')
            return
        }

        // 获取所选文件id列表
        var fileChecked = []
        $('#modal-file-sort .file-check:checked').each(function(i, e) {
            fileChecked.push($(e).attr('data-file-id'))
        })

        if (fileChecked.length == 0) {
            if (!confirm('未选择任何文件，将清空对应设备的播放列表，是否继续？')) {
                $modal.modal('hide')
                return false;
            }
        }
        $.post('/index.php/File/setPlayList', {
            fidList: fileChecked,
            deviceIdList: deviceIdList,
            type: type
        }, function(back) {
            alert(back.info)
            $modal.modal('hide')
        })
    })

    /**
     * 文件修改类
     */
    $('body').on('click', '.remove-file', function(event) {
        event.stopPropagation()
        var fid = $(this).attr('data-file-id')

        if (confirm('确定删除该文件吗？')) {
            $.post('/index.php/File/remove', {
                fid: fid
            }, function(back) {
                if (back.status == 1) {
                    $("#fileTr-" + fid).fadeOut(200, function() {
                        $(this).remove()
                    })
                } else {
                    alert(back.info)
                }
            })
        }
    })
    /* 修改文件名 */
    $('body').on('click', '.update-file', function(event) {
        event.stopPropagation()

        var self = $(this)
        var fid = self.attr('data-file-id')
        var $fileName = $('#fileList #fileTr-' + fid + ' .file-name')
        var fileNameOld = $fileName.text()

        var fileNameNew = prompt('请输入文件名称', fileNameOld)
        if (fileNameNew != null) {
            if (fileNameNew != '') {
                $.post("/index.php/File/updateFile", {
                    fid: fid,
                    fileName: fileNameNew
                }, function(back) {
                    if (back.status == 1) {
                        $fileName.text(fileNameNew)
                    } else {
                        alert(back.info)
                    }
                })
            } else {
                alert('文件名不能为空')
            }
        }
    })

    /* 修改文件标签类 */
    var GLOBAL_fileUpdateId = 0
    $('body').on('click', '.update-tag', function(event) {
        event.stopPropagation() // 避免触发tr的click事件
        var self = $(this)
        var fid = self.attr('data-file-id')
        var $modal = $('#modal-file-update-tag')
        GLOBAL_fileUpdateId = fid
        // 获取文件已经有的tagid列表
        var fileHasTagIdList = []
        $("#fileTr-" + fid + " .label").each(function(i, e) {
            fileHasTagIdList.push($(e).attr('data-tag-id'))
        })
        // 预选中已经包含的tag
        $("#file-update-tag span").removeClass('set-tag-checked')
        $.each(fileHasTagIdList, function(i, n) {
            $("#file-update-tag span[data-tag-id='" + n + "']").addClass('set-tag-checked')
        })
        $modal.modal('show')
    })
    $('#update-file-tag-submit').click(function() {
        var self = $(this)
        var fid = GLOBAL_fileUpdateId
        var tagIdCheckList = []
        var $modal = $('#modal-file-update-tag')

        $("#file-update-tag span.set-tag-checked").each(function(i, e) {
            tagIdCheckList.push($(e).attr('data-tag-id'))
        })

        $.post('/index.php/File/updateFileRel', {
            fid: fid,
            tagIdList: tagIdCheckList
        }, function(back) {
            alert(back.info)
            if (back.status == 1) {
                $modal.modal('hide')
            }
        })
    })

    /**
     * 标签选择控制类
     */
    $('body').off('click', '.set-tag', tagClick)
    $('body').off('click', '.set-tag-select-all', tagSelectAll)
    $('body').off('click', '.set-tag-select-none', tagSelectNone)
    $('body').off('click', '.set-tag-select-other', tagSelectOther)

    $('body').on('click', '.set-tag', deviceSearch, tagClick)
    $('body').on('click', '.set-tag-select-all', deviceSearch, tagSelectAll)
    $('body').on('click', '.set-tag-select-none', deviceSearch, tagSelectNone)
    $('body').on('click', '.set-tag-select-other', deviceSearch, tagSelectOther)

    /**
     *  通过theme(normal类tag)批量设置tv列表
     */
    $('.set-list-by-theme').click(function() {
        var self = $(this)
        var type = self.attr('data-type')
        var $devChecke = $("#theme-des .set-tag.set-tag-checked")
        var themeId = $devChecke.attr('data-tag-id')

        if ($devChecke.length > 0) {
            $.post('/index.php/PadApi/setThemeList', {
                type: type,
                themeId: themeId
            }, function(back) {
                alert(back.info)
            })
        } else {
            alert('未选择主题')
        }

    })

    /**
     * fileList 的便捷操作
     */
    $('body').on('click', '#select-fileList-all', function() {
        $("#fileList input[type=checkbox]").each(function(i, e) {
            $(e)[0].checked = true
        })
    })
    $('body').on('click', '#select-fileList-other', function() {
        $("#fileList input[type=checkbox]").each(function(i, e) {
            $(e)[0].checked = !$(e)[0].checked
        })
    })
})