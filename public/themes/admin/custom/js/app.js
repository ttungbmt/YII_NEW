$(function () {
    $('.styled').uniform()
    $('input[type="file"]').uniform({
        fileButtonClass: 'action btn btn-default',
        fileDefaultText: 'Vui lòng chọn file',
        fileBtnText: 'Chọn file',
    })
    // $('.form-group').find('input[type="checkbox"]', 'input[type="radio"]').uniform()
    // $('.form-group').find('input[type="checkbox"]', 'input[type="radio"]').uniform()

    $('[data-toggle="tooltip"]').tooltip()

    $("#btn-adv").click(() => $("#adv-content").slideToggle());

    $.LoadingOverlaySetup({
        imageColor: "#E85720",
        imageResizeFactor: 0.4
    })

    const floatThead = (selector) => {
        let $table = _.isString(selector) ? $(selector) : selector;
        $table.floatThead({
            responsiveContainer: function($table){
                $table.find('thead').css('background-color', 'white')
                return $table.closest('.table-responsive')
            }
        })
    }


    const readyPjax = () => {
        let pjaxCont = $("div[data-pjax-container]")
        floatThead(pjaxCont.find('table'))
        pjaxCont.on('pjax:timeout', function (e) {
            e.preventDefault()
        }).on('pjax:send', function () {
            pjaxCont.LoadingOverlay("show");
        }).off('pjax:complete').on('pjax:complete', function () {
            pjaxCont.LoadingOverlay("hide", true)
            floatThead($(this).find('table'))
            $('[data-toggle="tooltip"]').tooltip()
        })
    }

    $('a[data-action]').click(function (e) {
        e.preventDefault()
        let {selector, toggle, action, options, ...rest} = $(this).data()
        switch (action) {
            case 'download':
                $(selector).tableExport(Object.assign({type: 'excel'}, rest, options))
                break
            default:

                break
        }

    })

    $('div[data-action]').each(function (index) {
        let {action, url, timeout = 0} = $(this).data()
        $(this).LoadingOverlay('show')
        setTimeout(() => {
            $.get(url, (resp) => {
                $(this)
                    .html(resp)
                    .LoadingOverlay('hide', true);

                readyPjax()
            }).fail(() => {
                $(this)
                    .LoadingOverlay('hide', true)
                    .html(`Loading error`)
            })
        }, timeout)
    })

    readyPjax()
})
