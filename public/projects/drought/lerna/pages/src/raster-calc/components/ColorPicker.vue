<template>
    <div>
        <input type="hidden" v-model="value"/>

        <popper trigger="clickToToggle" :options="{placement: 'top'}">
            <div class="popper">
                <v-sketch @input="setColor" :value="value"/>
            </div>

            <input type="text" v-model="value" slot="reference" class="btn-color" :style="{background: value}" :name="name"/>
        </popper>
    </div>
</template>
<script>
    import Popper from 'vue-popperjs'
    import 'vue-popperjs/dist/vue-popper.css'
    import {Sketch} from 'vue-color'

    export default {
        inheritAttrs: false,
        props: {
            name: {
                type: String,
            },
            value: {
                type: String,
                default: 'white'
            },
        },
        data() {
            return {
            }
        },
        components: {
            'popper': Popper,
            'v-sketch': Sketch
        },
        methods: {
            updateValue: function (value) {
                this.$emit('input', value)
            },
            setColor({hex}){
                this.$emit('input', hex)
            },
        }
    }
</script>

<style lang="scss" scoped>
    .btn-color {
        box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2);
        margin-top: 5px;
        border: none;
        outline: none;
        cursor: pointer;
        width: 75px;
        height: 28px;
        border-radius: 2px;
        font-size: 12px;
        color: white;
        padding: 0 8px;
        text-align: center;
        text-shadow: 1px 1px 1px #6d6666;
    }

    .popper {
        width: auto;
        background-color: #fafafa;
        border: none;
        box-shadow: none;
    }

</style>