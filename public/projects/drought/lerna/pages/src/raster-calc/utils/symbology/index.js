import {get, find, isEmpty, isUndefined, map} from 'lodash-es'
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

export const classesToSymbols = (data) => {
    return map(data, (v, k) => {
        if (!isUndefined(data[k + 1])) {
            let start = v,
                end = data[k + 1]

            return {
                start,
                end,
                legend: `${start} - ${end}`
            }
        }

        return null
    }).filter(v => v)
}

export const appendColorSymbols = (symbols, colors) => {
    return map(symbols, (v, k) => {
        return {
            ...v,
            color: colors[k]
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
