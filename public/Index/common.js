$(function(){
    $('#file_upload').uploadify({
        'swf' : '/public/lib/uploadify/uploadify.swf',
        'uploader' : '/index.php/Upload/fileUpload',
        'fileObjName' : 'file',
        'progressData' : 'speed',
        'removeTimeout' : 0,
        'fileSizeLimit' : '6000MB',
        'auto' : false,
        'needUpdateConfig' : false,

        'onSelect' : function(file) {

        },
        'onUploadStart' : function(file) {
            var deviceIdList = []
            var tagIdList = []

            $("#device-set .set-tag-checked").each(function(i, e){
                deviceIdList.push($(e).attr('data-tag-id'))
            })
            $("#tag-set .set-tag-checked").each(function(i, e){
                tagIdList.push($(e).attr('data-tag-id'))
            })

            var formData = {
                'deviceIdList[]': deviceIdList,
                'tagIdList[]': tagIdList,
            }
            $("#file_upload").uploadify("settings", "formData", formData)
        },
        'onUploadSuccess': function(file, data, response) {
            var back = eval("("+data+")")
            var style = back.code==1? 'text-success': 'text-danger'
            this.needUpdateConfig = back.code==1? true: false
            var $li = $("<li class='"+style+"'>"+back.status+" "+file.name+"</li>")
            $("#upload-result").append($li)
        },
        'onQueueComplete' : function(queueData) {
            // 通知更新file_map.xml
            if(this.needUpdateConfig == true){
                $.post('/index.php/File/updateConfig', {}, function(back){
                    if(back.status == 1){
                        $("#device-set .set-tag, #tag-set .set-tag").removeClass('set-tag-checked')
                    }else{
                        alert(back.info)
                    }
                })
            }
        },
    });

    $("#submit").click(function(){
        var tagInnerList = []
        var tagNormalList = []
        this.needUpdateConfig = false

        $("#device-set .set-tag.set-tag-checked").each(function(i, e){
            tagInnerList.push($(e).val())
        })
        $("#tag-set .set-tag.set-tag-checked").each(function(i, e){
            tagNormalList.push($(e).val())
        })
        // 检查是否选择了设备和标签
        if(tagInnerList.length>0){
            // 清空result列表
            $("#upload-result").empty()
            $("#file_upload").uploadify('upload', '*')
        }else{
            alert('至少选择一个设备')
        }
    })

    /* 提交标签 */
    
    $("#add-tag").click(function(){
        var tagName = prompt('请输入标签名称')

        if(tagName != null){
            if(tagName != ''){
                $.post("/index.php/Tag/create", {tagName: tagName, fetch: false}, function(back){
                    if(back.status == 1){
                        var tagId = back.data
                        var $tagSpan = $("<span class='set-tag' data-tag-id='"+tagId+"'>"+tagName+"</span>")
                        $("#tag-set").append($tagSpan)
                    }else{
                        alert(back.info)
                    }
                })
            }else{
                alert('标签名不能为空')
            }
        }
    })
})