import {mapActions, mapMutations} from 'vuex'

export default {
    beforeMount(){
        // this.defaultValues && this.initialForm(this.defaultValues)
    },
    methods: {
        ...mapMutations('form', [
            'resetForm',
            'submitForm',
            'initialForm',
        ]),
    }
}