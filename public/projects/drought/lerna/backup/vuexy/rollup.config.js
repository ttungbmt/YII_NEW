import path from 'path'
import {map} from 'lodash'
import pkg from './package.json'
import cjs  from '@rollup/plugin-commonjs'
import json from '@rollup/plugin-json'
import alias from 'rollup-plugin-alias'
import postcss from 'rollup-plugin-postcss'
import babel from 'rollup-plugin-babel'
import vue from 'rollup-plugin-vue'
import node from '@rollup/plugin-node-resolve'
import replace from '@rollup/plugin-replace'
import progress from 'rollup-plugin-progress'

const version = process.env.VERSION || pkg.version

const banner =
    `/**
 * vuexy v${version}
 * (c) ${new Date().getFullYear()} Truong Thanh Tung
 * @license MIT
 */`

const resolve = _path => path.resolve(__dirname, _path)

const configs = {
    umdDev: {
        input: resolve('src/index.js'),
        file: resolve(pkg.unpkg),
        format: 'umd',
        env: 'development'
    },
    // umdProd: {
    //     input: resolve('src/index.js'),
    //     file: resolve('dist/vuexy.min.js'),
    //     format: 'umd',
    //     env: 'production'
    // },
    // commonjs: {
    //     input: resolve('src/index.js'),
    //     file: resolve(pkg.main),
    //     format: 'cjs'
    // },
    // esm: {
    //     input: resolve('src/index.js'),
    //     file: resolve(pkg.module),
    //     format: 'es'
    // },
}

function genConfig (opts) {
    const external = [
        'vue'
    ]

    const globals = {
        'vue': 'Vue'
    }


    const config = {
        input: resolve('src/index.js'),
        output: {
            file: opts.file,
            format: opts.format,
            banner,
            name: 'Vuexy',
            globals,
        }
    }

    // if (opts.env) {
    //     config.output.plugins.unshift(replace({
    //         'process.env.NODE_ENV': JSON.stringify(opts.env)
    //     }))
    // }

    config.external = external
    config.plugins = [
        node(),
        cjs(),
        babel({
            presets: [
                ['@babel/preset-env', {modules: false}],
                '@babel/preset-react',
                '@babel/preset-flow',
            ],
            plugins: [
                '@babel/plugin-proposal-export-default-from',
                '@babel/plugin-proposal-export-namespace-from',
                // '@babel/plugin-proposal-throw-expressions',
                // '@babel/plugin-proposal-class-properties',
                // '@babel/plugin-syntax-dynamic-import',
                // '@babel/plugin-proposal-object-rest-spread',
                // '@babel/plugin-proposal-optional-chaining',
                // ['@babel/plugin-proposal-decorators', {'legacy': true}],
            ],
            exclude: 'node_modules/**',
            runtimeHelpers: true,
        }),
        // alias({
        //     entries: {
        //         '@': resolve('src'),
        //     }
        // }),
        json(),
    ]

    return config
}

export default map(configs).map(genConfig)

