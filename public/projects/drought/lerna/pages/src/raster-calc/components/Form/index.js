import {createHelpers} from 'vuex-map-fields'
export {formMixin} from './mixins'

export const mapFormFields = (fields) => createHelpers({
    getterType: 'getFormField',
    mutationType: 'updateFormField',
}).mapFields('form', fields)

export {default as Form} from './Form.vue'
