import {get, find, isEmpty, isUndefined, map, isNil, reverse} from 'lodash-es'
import chroma from 'chroma-js'
import geostats from 'geostats'

export const generateColorRamp = (number = 100) => {
    return map(chroma.brewer, (v, k) => ({key: k, colors: chroma.scale(k).colors(number)}))
}

export const methods = [
    {key: 'ei', name: 'Equal Interval '},
    {key: 'qc', name: 'Equal Count (Quantile)'},
    {key: 'sd', name: 'Standard Deviation'},
    {key: 'ap', name: 'Arithmetic Progression'},
    {key: 'gp', name: 'Geometric Progression'},
    {key: 'nb', name: 'Natural Breaks (Jenks)'},
    {key: 'uv', name: 'Uniques Values'},
]


export const symbology = {
    methods,
    mode: 'nb',
    number: 5,
}

export const scaleColors = (scale = 'Spectral', number = symbology.number) => chroma.scale(isEmpty(scale) ? 'Spectral' :  scale).colors(number)

export const geoClassify = (data, method = 'nb', nbClass = symbology.number) => {
    let stats = new geostats(data),
        methodFn = get(statFns, method)

    return stats[methodFn](nbClass)
}

export const classesToSymbols = (data, legendFormat = '1% - 2%') => {
    return map(data, (v, k) => {
        if (!isUndefined(data[k + 1])) {
            let start = v,
                end = data[k + 1]

            return {
                start,
                end,
                legend: legendFormat
                    .replace('1%', isNil(start)? '' : start)
                    .replace('2%', isNil(end)? '' : end)
                    .replace(' - ', isNil(start)? '' : ' - ')
            }
        }

        return null
    }).filter(v => v)
}

export const appendColorSymbols = (symbols, colors, invert = false) => {
    let _colors = isEmpty(invert) ? colors : reverse(colors)

    return map(symbols, (v, k) => {
        return {
            ...v,
            color: _colors[k]
        }
    })
}



const statFns = {
    ei: 'getClassEqInterval',
    qc: 'getClassQuantile',
    sd: 'getClassStdDeviation',
    ap: 'getClassArithmeticProgression',
    gp: 'getClassGeometricProgression',
    nb: 'getClassJenks',
    uv: 'getClassUniqueValues',
}
