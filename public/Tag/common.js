$(function(){
    $("#btn-add-tag").click(function(){
        var tagName = prompt('请输入标签名称')

        if(tagName != null){
            if(tagName != ''){
                $.post("/index.php/Tag/create", {tagName: tagName}, function(back){
                    if(back.status == 1){
                        var $tagTr = $(back.data)
                        var $tagList = $("#tagList")
                        $tagTr.prependTo($tagList)
                    }else{
                        alert(back.info)
                    }
                })
            }else{
                alert('标签名不能为空')
            }
        }
    })

    $('body').on('click', '.remove-tag', function(){
        if(confirm('删除标签？')){
            var self = this
            var tagId = $(self).attr('data-tag-id')

            $.post("/index.php/Tag/remove", {tagId: tagId}, function(back){
                if(back.status == 1){
                    var $tagTr = $("#tagTr-"+tagId)
                    $tagTr.fadeOut(200, function(){$(this).remove()})
                }else{
                    alert(back.info)
                }
            })
        }
    })
})