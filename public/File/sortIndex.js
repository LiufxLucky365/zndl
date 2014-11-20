$(function(){
    $("#tab-file-sort tr").click(function(){
        var self = $(this)
        if(event.ctrlKey){
            self.addClass('info')
            // 两.info之间的tr也选择
            var $checkList = $("#tab-file-sort tr.info")
            var start = $($checkList[0]).index()
            var end = $($checkList[$checkList.length-1]).index()
            $("#tab-file-sort tr").each(function(i, e){
                if(i>=start && i<=end){
                    $(e).addClass('info')
                }
            })
            return
        }
        self.addClass('info').siblings().removeClass('info')
    })

    $('#sort-mv-up').click(function(){
        var $fileList = $('#tab-file-sort tr.info')
        if($fileList.length > 0){
            var $prev = $($fileList[0]).prev()
            if($prev.length > 0){
                $fileList.insertBefore($prev)
            }else{
                alert('已经是最前')
            }
        }else{
            alert('未选择行1')
        }
    })
    $('#sort-mv-down').click(function(){
        var $fileList = $('#tab-file-sort tr.info')
        if($fileList.length > 0){
            var $end = $($fileList[$fileList.length-1]).next()
            if($end.length > 0){
                $fileList.insertAfter($end)
            }else{
                alert('已经是最后')
            }
        }else{
            alert('未选择行')
        }
    })

    $("#submit-sort").click(function(){
        var fidList = []

        $("#tab-file-sort tr").each(function(i, e){
            fidList.push($(e).attr('data-fid'))
        })

        $.post('/index.php/File/sort', {fidList: fidList}, function(back){
            alert(back.info)
        })
    })
})