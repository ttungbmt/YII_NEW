// let id = $('.kv-panel-pager').parents('div[data-pjax-container]').attr('id')
// https://github.com/andydkcat/yii2-custom-linkpager/blob/master/widgets/demopager/assets/js/demopager.js

$(function () {
    class PagerWidget {
        options = {}

        init(options) {
            if (options.url === "undefined" || !(options.url)) {
                return;
            }

            this.options = options

            this.ready()
        }

        bindEvent(container, url) {
            $(container).find('select[name="per-page"]').on('change', (e) => {
                let selectedPageSize = e.target.value;
                this.reload(container, url, {'per-page': selectedPageSize})
            })

            $(container).find('input[name="page"]').on('keydown', (e) => {
                if (e.which === 13) {
                    this.reload(container, url, {page: e.target.value})
                }
            })
        }

        ready() {
            let url = this.options.url
            $('div[data-pjax-container]').each((i, obj) => {
                let container = '#'+$(obj).attr('id')
                $(container).on('pjax:complete', () => this.bindEvent(container, url))
                this.bindEvent(container, url)
            })
        }

        reload(container, link, params) {
            let uri = URI(link)
            uri.search(data => ({...data, ...params}));
            $.pjax({url: uri.toString(), container})
        }
    }

    window.pagerWidget = new PagerWidget
})

