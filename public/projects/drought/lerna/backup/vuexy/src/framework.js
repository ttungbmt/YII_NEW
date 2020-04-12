import { install } from './install'
import Vue from 'vue'
import VueWait from 'vue-wait'

class Vuetify {
    constructor(config) {
        this.bus = new Vue()
        this.bootingCallbacks = []
        this.config = config
    }

    $mount(elementOrSelector){
        this.app = new Vue({
            wait: new VueWait({
                useVuex: true,
            }),
        })

        this.app.$mount(elementOrSelector)
    }

    /**
     * Register a listener on Nova's built-in event bus
     */
    $on(...args) {
        this.bus.$on(...args)
    }

    /**
     * Register a one-time listener on the event bus
     */
    $once(...args) {
        this.bus.$once(...args)
    }

    /**
     * Unregister an listener on the event bus
     */
    $off(...args) {
        this.bus.$off(...args)
    }

    /**
     * Emit an event on the event bus
     */
    $emit(...args) {
        this.bus.$emit(...args)
    }
}

Vuetify.install = install
Vuetify.installed = false
// Vuetify.version = __VUEXY_VERSION__

export default Vuetify

