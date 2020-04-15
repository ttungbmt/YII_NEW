<template>
    <b-form-select :value="value" @input="onInput" v-bind="options" :options="innerItems"></b-form-select>
</template>
<script>
    import {pick} from 'lodash-es'

    export default {
        name: 'v-select',
        props: {
            items: {
                type: [Array, Object],
            },
            value: {
                type: String,
            },
            className: {
                type: String,
            },
            placeholder: {
                type: String,
            },
            name: {
                type: String,
            },
        },
        computed: {
            options() {
                return pick(this, ['className', 'placeholder', 'name'])
            },
            innerItems(){
                return this.items
            }
        },
        data() {
            return {

            }
        },

        methods: {
            onInput (value) {
                this.$emit('input', value)
            }
        }
    }
</script>