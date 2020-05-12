import Vue from 'vue'
import chroma from 'chroma-js'
import {showTiff} from './utils'
import {get, includes} from 'lodash-es'

import './utils/symbology/style.scss'
import {
    generateColorRamp,
    symbology,
    scaleColors,
    geoClassify,
    classesToSymbols,
    appendColorSymbols
} from './utils/symbology'

import {Sketch} from 'vue-color'
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
        beforeCreate() {
            this.$store.registerModule(Sym.name, Sym)
        },
        mounted() {
            $('#btnChoseImg').change(this.onChangeBand)
            $('#drop-year').change((e) => {
                window.location.href = this.redirectYear.replace('{year}', 'year=' + e.target.value)
            })

            this.updateBoxBand()

            if (this.existFile) {
                showTiff('#preview-tiff', this.tiffUrl)
            }
        },

        computed: {
            ...mapState('symbology', [
                'symbols',
                'colorRamp',
            ]),
            methods() {
                return [
                    {text: 'CDI', value: 'cdi'},
                    {text: 'SPI', value: 'spi'},
                    {text: 'NDVI', value: 'ndvi'},
                    {text: 'LST', value: 'lst'},
                ].concat(get(this.$store, `state.${Sym.name}.methods`, []))
            },
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
                    colors = scaleColors(this.gradientSelected, nbClass),
                    vStat = null,
                    legend = []

                switch (mode){
                    case 'cdi':
                        vStat =  [null, 1, 2, 3, 4, null]
                        legend = ['<=1 (Hạn khắc nghiệt)', '1 - <=2 (Hạn nặng)', '2 - <=3 (Hạn trung bình)', '3 - <=4 (Hạn nhẹ)', '> 4 (Không hạn)']
                        break;
                    case 'spi':
                        vStat =  [null, -2, -1.5, -1, 0, null]
                        legend = ['SPI <= -2 (Hạn cực nặng)', '-2 < SPI <= -1.5 (Hạn nặng)', '-1.5 < SPI <= -1 (Hạn vừa)', '-1 < SPI <= 0 (Hạn nhẹ)', 'SPI > 0 (Không hạn)']
                        break;
                    case 'ndvi':
                    case 'lst':
                        vStat =  [null, -50, -25, -10, 0, null]
                        legend = ['<= -50 (Hạn rất nặng)', '-50 : <= -25 (Hạn nặng)', '-25 : <= -10 (Hạn vừa)', '-10 : <= 0 (Hạn nhẹ)', '> 0 (Không hạn)']
                        break;
                    default:
                        vStat = geoClassify(this.statData, mode, nbClass)
                        break;
                }

                this.symForm.symbols = classesToSymbols(vStat, legendFormat)

                if (includes(['cdi', 'spi', 'lst', 'ndvi'], mode)) {
                    this.symForm.symbols = this.symForm.symbols.map((v, k) => {
                        v.legend = get(legend, k, v.legend)
                        return v
                    })
                }

                this.symForm.symbols = appendColorSymbols(this.symForm.symbols, colors, invertColorRamp)
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