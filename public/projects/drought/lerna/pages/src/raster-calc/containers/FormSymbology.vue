<template>
    <form :action="action">
        <slot/>
    </form>
</template>
<script>
    import {transform, isPlainObject, isArray, find, get, last} from 'lodash-es'
    import {mapMutations, mapActions} from 'vuex'
    import {formMixin} from "../components/Form/mixins"

    const mapFormFields = (key, fields) => {
        const getValue = (items, formKey, name) => {
            return get(find(items, {key: formKey}), 'values.'+name)
        }

        const getPath = (items, formKey, name) => {
            return `items[${index}].values.${name}`
        }

        const getComputer = (key, v) => {
            return {
                get(){
                    return this.$store.getters['form/getValue'](key, v)
                },
                set(value){
                    this.$store.commit('form/updateField', {
                        path: this.$store.getters['form/getPathValue'](key, v),
                        value
                    })
                }
            }
        }

        return transform(fields, (result, v, k) => {
            if(isPlainObject(fields)){
                result[k] = getComputer(key, v)
            } else if(isArray(fields)){
                let name = last(v.split('.'))

                result[name] = getComputer(key, v)
            }

        }, {})
    }

    const FORM = 'form1'


    export default {
        mixins: [formMixin],
        props: {
            action: String
        },
        computed: {
            ...mapFormFields(FORM, [
                'legendFormat',
                'colorRamp',
                'mode',
                'nbClass',
                'symbols'
            ]),
        },
        data() {
            return {
                defaultValues: {
                    legendFormat: '%1  - %2',
                    colorRamp: 'OrRd',
                    mode: 'nb',
                    nbClass: 6
                }
            }
        },
        mounted() {
            this.initialForm({formKey: FORM, defaultValues: this.defaultValues})
        },
        methods: {}
    }
</script>