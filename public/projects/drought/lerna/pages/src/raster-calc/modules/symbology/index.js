import {generateColorRamp, symbology} from '../../utils/symbology'
import {upperFirst} from 'lodash-es'

export default {
    namespaced: true,
    name: 'symbology',
    state: {
        symbols: [],
        colorRamp: generateColorRamp(),
        methods: symbology.methods.map(v => ({text: upperFirst(v.name), value: v.key}))
    },
    mutations: {
        initState(state, payload) {

        }
    }
}