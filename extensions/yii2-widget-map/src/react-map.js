import {render} from 'react-dom'
import ReactMap from './ReactMap'

$.fn.reactMap = function (option) {
    let args = Array.apply(null, arguments), retvals = [];
    args.shift();

    this.each(function () {
        let self = $(this), data = self.data('reactMap'), options = typeof option === 'object' && option, opts = {}

        if (!data) {
            $.extend(true, opts, $.fn.reactMap.defaults, options, self.data());
            render(<ReactMap element={this} {...opts}/>, this)
        }

        if (typeof option === 'string') {
            option === 'destroy' || unmountComponentAtNode(this)
            console.warn('Developing...')
            // retvals.push(data[option].apply(data, args));
        }
    });
    switch (retvals.length) {
        case 0:
            return this;
        case 1:
            return retvals[0];
        default:
            return retvals;
    }
}

$.fn.reactMap.defaults = {
    center: [10.804476, 106.639384],
    zoom: 10,
    zoomControl: false,
}
