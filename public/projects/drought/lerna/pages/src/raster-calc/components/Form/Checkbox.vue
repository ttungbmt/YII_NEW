
<template>
    <b-form-checkbox-group v-bind="options" v-model="innerValue" :options="innerItems" class="mt4"></b-form-checkbox-group>

</template>
<script>
    import {pick} from 'lodash-es'

    export default {
        name: 'v-checkbox',
        props: {
            items: {
                type: [Array, Object],
            },
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
                innerValue: this.value,
                options: {
                    ...pick(this, ['type', 'placeholder' , 'name'])
                },
            }
        },
        watch: {
            // Handles internal model changes.
            innerValue(newVal) {
                this.$emit('input', newVal);
            },
            // Handles external model changes.
            value(newVal) {
                this.innerValue = newVal;
            }
        },
        computed: {
            innerItems(){
                return this.items
            },
        },
        mounted() {
            console.log(this.value, this.innerItems);
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