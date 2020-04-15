<template>
    <div class="form-group">
        <label class="d-block" v-if="label" v-html="label"></label>
        <component v-bind:is="component" v-bind="options" :value="value" @input="onInput"></component>
    </div>
</template>
<script>
    import {pick, transform} from 'lodash-es'
    import Input from './Input.vue'
    import Select from './Select.vue'
    import Checkbox from './Checkbox.vue'

    const fields = {
        Input, Select, Checkbox
    }

    const components = transform(fields, (result, v, k) => {
        if(v.name) result[v.name] = v
    }, {})

    export default {
        name: 'v-field',
        inheritAttrs: false,
        props: ['value', 'as', 'label', 'placeholder', 'type', 'items', 'name'],
        computed: {
            component(){
                switch (this.as) {
                    case 'select':
                        return Select.name
                    case 'radio':
                        return 'v-radio'
                    case 'checkbox':
                        return Checkbox.name
                    default:
                        return Input.name
                }
            },
            options(){
                return pick(this, ['type',  'placeholder', 'items', 'name'])
            }
        },
        components,
        data(){
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