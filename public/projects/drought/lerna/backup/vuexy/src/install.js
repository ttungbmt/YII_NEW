import Vue from 'vue'
import axios from 'axios'
import VueWait from 'vue-wait'
import PortalVue from 'portal-vue'
import Vuex from 'vuex'
import VueRouter from 'vue-router'
import VueNoty from 'vuejs-noty'
import AsyncComputed from 'vue-async-computed'
import {assign} from 'lodash-es'
import {registerComponents, registerDirectives, registerPlugins} from "./utils/plugins";

// import 'vuejs-noty/dist/vuejs-noty.css'

export function install(Vue, args = {}) {
    if (install.installed) return
    install.installed = true

    const components = args.components || {}
    const directives = args.directives || {}
    const plugins = args.plugins || {}
    const mixins = args.mixins || {}

    const innerPlugins = {
        VueWait,
        PortalVue,
        Vuex,
        VueRouter,
        VueNoty,
        AsyncComputed,
    }

    registerComponents(Vue, components)
    registerDirectives(Vue, directives)
    // registerPlugins(Vue, assign(innerPlugins, plugins))

    // Used to avoid multiple mixins being setup
    // when in dev mode and hot module reload
    // https://github.com/vuejs/vue/issues/5089#issuecomment-284260111
    if (Vue.$_vuexy_installed) return
    Vue.$_vuexy_installed = true


    Vue.mixin(mixins)
    Vue.prototype.$http = axios

    window.axios = axios
}