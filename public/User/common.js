$(function(){
    $("#submit").click(function(){
        var $form = $("#alter-user-pwd")
        $.post('/index.php/User/update', $form.serialize(), function(back){
            alert(back.info)
        })
        return false
    })
})