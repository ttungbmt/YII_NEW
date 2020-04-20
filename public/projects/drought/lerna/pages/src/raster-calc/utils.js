export const showTiff = (selector, url) => {
    if(!(selector && url) ) return;

    let xhr = new XMLHttpRequest()
    xhr.responseType = 'arraybuffer'

    xhr.open('GET', url);
    xhr.onload = function (e) {
        let tiff = new Tiff({buffer: xhr.response});
        let canvas = tiff.toCanvas()
        $(selector).append(canvas)
    };
    xhr.send()
}