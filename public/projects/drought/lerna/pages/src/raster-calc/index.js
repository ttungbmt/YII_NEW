import Vue from 'vue'
import chroma from 'chroma-js'
import geostats from 'geostats'
import {showTiff} from './utils'

import {map, isUndefined} from 'lodash-es'
import './utils/symbology/style.scss'
import {generateColorRamp, symbology, scaleColors, geoClassify, classesToSymbols, appendColorSymbols} from './utils/symbology'
import OperaterPanel from './OperaterPanel.vue'


Vue.component('v-panel-operator', OperaterPanel)

import Vuexy from '@ttungbmt/vuexy'


// console.log(Vuexy);

$(function () {

    new Vuexy({
        data: {
            ...globalStore,
            symbology,
            symbols: [],
            colorRamp: []
        },
        mounted() {
            console.log(this.$noty);
            $('#btnChoseImg').change(this.onChangeBand)

            if (this.existFile) {
                this.colorRamp = _.map(this.colorRamp, (v, k) => ({key: k, colors: chroma.scale(k).colors(100)}))

                this.updateBoxBand()
                this.colorRamp = generateColorRamp()
                showTiff('#preview-tiff', this.tiffUrl)
            }
        },

        computed: {
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
                let nbClass = this.symbology.number,
                    vStat = geoClassify(this.statData, this.symbology.mode, nbClass),
                    colors = scaleColors(this.gradientSelected, nbClass)

                this.symbols = classesToSymbols(vStat)
                this.symbols = appendColorSymbols(this.symbols, colors)
            },
            addOpr(v) {
                this.form.expr += v
            },
            onChangeBand(e) {
                let ids = $(e.target).select2('data').map(v => parseInt(v.id))
                this.selected = ids
                this.updateBoxBand()
            },

            updateBoxBand() {
                let self = this
                if (!this.selected) return null

                $(this.$el).find('#box-band').html(this.selected.map(v => `<option value="${v}">${this.bands_arr[v]}</option>`).join(''))

                $('#box-band option').dblclick(function () {
                    let id = $(this).val()
                    self.expr += `[${self.bands_arr[id]}]`
                });
            }
        }
    }).$mount( '#vue-app')
})