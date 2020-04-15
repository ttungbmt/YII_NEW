import Vue from 'vue'
import chroma from 'chroma-js'
import {showTiff} from './utils'
import {pick} from 'lodash-es'

import './utils/symbology/style.scss'
import {
    generateColorRamp,
    symbology,
    scaleColors,
    geoClassify,
    classesToSymbols,
    appendColorSymbols
} from './utils/symbology'

import { Sketch } from 'vue-color'
import OperaterPanel from './OperaterPanel.vue'
import Vuexy from '@ttungbmt/vuexy'
import {mapState} from 'vuex'

import {ColorPicker, ColorRamp, Field} from './components'
import {FormSymbology} from './containers'
import Sym from './modules/symbology'

Vue.config.productionTip = false

$(function () {
    Vue.component('v-panel-operator', OperaterPanel)
    Vue.component('v-color-picker', ColorPicker)
    Vue.component('v-color-ramp', ColorRamp)
    Vue.component('v-form-symbology', FormSymbology)

    Vue.component('v-field', Field)


    new Vuexy({
        data: {
            ...globalStore,
        },
        beforeCreate(){
            this.$store.registerModule(Sym.name, Sym)
        },
        mounted() {
            $('#btnChoseImg').change(this.onChangeBand)
            this.updateBoxBand()

            if (this.existFile) {
                showTiff('#preview-tiff', this.tiffUrl)
            }
        },

        computed: {
            ...mapState('symbology', [
                'symbols',
                'colorRamp',
                'methods',
            ]),
            bands() {
                return _.map(this.selected).join(',')
            }
        },
        methods: {
            activeGradient(e) {
                $('.gradient-line').removeClass('active')
                let current = $(e.currentTarget)
                current.addClass('active')
                this.gradientSelected = current.data('key')
            },
            generateColors() {
                let {nbClass, legendFormat, mode, symbols, invertColorRamp} = this.symForm,
                    vStat = geoClassify(this.statData, mode, nbClass),
                    colors = scaleColors(this.gradientSelected, nbClass)

                this.symForm.symbols = classesToSymbols(vStat, legendFormat)
                this.symForm.symbols = appendColorSymbols(this.symForm.symbols , colors, invertColorRamp)
            },
            addOpr(v) {
                this.form.expr += v
            },
            onChangeBand(e) {
                let ids = $(e.target).select2('data').map(v => parseInt(v.id))
                this.form.bands = ids
                this.updateBoxBand()
            },

            updateBoxBand() {
                let self = this
                // if (!this.selected) return null

                $(this.$el).find('#box-band').html(this.form.bands.map(v => `<option value="${v}">${this.bands_arr[v]}</option>`).join(''))

                $('#box-band option').dblclick(function () {
                    let id = $(this).val()
                    self.form.expr += `[${self.bands_arr[id]}]`
                });
            }
        }
    }).$mount('#vue-app')
})