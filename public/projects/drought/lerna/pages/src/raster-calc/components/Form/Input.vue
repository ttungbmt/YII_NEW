<template>
    <b-form-input @input="onInput" v-bind="options" :value="innerValue"></b-form-input>
</template>
<script>
    import {pick} from 'lodash-es'

    export default {
        name: 'v-input',
        props: {
            value: {
            },
            type: {
                type: String,
                default: 'text'
            },
            name: {
                type: String,
            },
            className: {
                type: String,
                default: 'form-control'
            },
            placeholder: {
                type: String,
            }
        },
        data() {
            return {
                options: {
                    ...pick(this, ['type', 'placeholder' , 'name'])
                },
            }
        },
        computed: {
            innerValue(){
                return this.parseValue(this.value)
            }
        },
        methods: {
            parseValue(input){
                if(this.type === 'number') return parseFloat(input)

                return input
            },
            onInput (value) {
                this.$emit('input', this.parseValue(value))

            }
        }
    }
</script>