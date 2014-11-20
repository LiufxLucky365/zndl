$(function(){
    $('.set-tag').click(function(event){
        event.stopPropagation()

        var self = $(this)
        var uid = self.attr('data-user-id')
        var deviceId = self.attr('data-tag-id')
        var data = {
            uid: uid,
            deviceId: deviceId,
        }

        if(self.hasClass('set-tag-checked')){
            // 移除权限
            $.post('/index.php/User/removeUserDeviceAuth', data, function(back){
                if(back.status == 1){
                    self.removeClass('set-tag-checked')
                }else{
                    alert(back.info)
                }
            })
        }else{
            // 添加权限
            $.post('/index.php/User/addUserDeviceAuth', data, function(back){
                if(back.status == 1){
                    self.addClass('set-tag-checked')
                }else{
                    alert(back.info)
                }
            })
        }
    })

    $('.remove-user').click(function(){
        var self = $(this)
        var uid = self.attr('data-user-id')

        if(confirm('确定删除该用户吗？')){
            $.post('/index.php/User/removeUser', {uid: uid}, function(back){
                if(back.status == 1){
                    $('#tr-user-'+uid).remove()
                }else{
                    alert(back.info)
                }
            })
        }
    })

    $("#add-user").click(function(){
        var uname = prompt('请输入用户名称')

        if(uname != null){
            if(uname != ''){
                var url = '/index.php/User/createUser/uname/'+uname
                window.location.href = url
            }else{
                alert('用户名不能为空')
            }
        }
    })
})