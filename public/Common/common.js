/**
 * 标签选择控制类
 */
$('body').on('click', '.set-tag', tagClick)
$('body').on('click', '.set-tag-select-all', tagSelectAll)
$('body').on('click', '.set-tag-select-none', tagSelectNone)
$('body').on('click', '.set-tag-select-other', tagSelectOther)

function tagClick(arg) {
    var self = $(this)

    if (event.ctrlKey != true) {
        self.siblings().andSelf().removeClass('set-tag-checked')
    }
    self.toggleClass('set-tag-checked');

    if (arg.data != undefined && self.attr('data-callback') != undefined) {
        arg.data()
    }
}

function tagSelectAll(arg) {
    var self = $(this)
    var $des = $('#' + self.attr('data-ctrl-id'))
    $des.find('.set-tag').addClass('set-tag-checked')

    if (arg.data != undefined && self.attr('data-callback') != undefined) {
        arg.data()
    }
}

function tagSelectNone(arg) {
    var self = $(this)
    var $des = $('#' + self.attr('data-ctrl-id'))
    $des.find('.set-tag').removeClass('set-tag-checked')

    if (arg.data != undefined && self.attr('data-callback') != undefined) {
        arg.data()
    }
}

function tagSelectOther(arg) {
    var self = $(this)
    var $des = $('#' + self.attr('data-ctrl-id'))
    $des.find('.set-tag').toggleClass('set-tag-checked')

    if (arg.data != undefined && self.attr('data-callback') != undefined) {
        arg.data()
    }
}