<template>
    <div>
        <label class="typo__label">{{label}}</label>
        <multiselect v-model="value" :placeholder="placeholder" label="title" track-by="title" :options="options" :option-height="500" :custom-label="customLabel" :show-labels="false" :close-on-select="false">
            <template slot="singleLabel" slot-scope="props">
                <img class="option__image" :src="props.option.img" alt="No Man’s Sky">
                <span class="option__desc">
                    <span class="option__title">{{ props.option.title }}</span>
                </span>
            </template>
            <template slot="option" slot-scope="props">
                <img class="option__image" :src="props.option.img" alt="No Man’s Sky">
                <div class="option__desc"><span class="option__title">{{ props.option.title }}</span><span class="option__small">{{ props.option.desc }}</span></div>
            </template>
        </multiselect>
        <div style="margin-bottom: 50px"></div>
    </div>
</template>
<script>
    // import 'vue-multiselect/dist/vue-multiselect.min.css'
    import Multiselect from 'vue-multiselect'

    export default {
        props: {
            // value: {
            //     type: String,
            //     default: 'OrRd'
            // },
        },
        components: {
            Multiselect
        },
        data() {
            return {
                label: 'Color Ramp',
                placeholder: 'Choose color ramp...',
                value: { title: 'Explorer', desc: 'Discovering new species!', img: 'static/posters/creatures.png' },
                options: [
                    { title: 'Space Pirate', desc: 'More space battles!', img: 'static/posters/fleet.png' },
                    { title: 'Merchant', desc: 'PROFIT!', img: 'static/posters/trading_post.png' },
                    { title: 'Explorer', desc: 'Discovering new species!', img: 'static/posters/creatures.png' },
                    { title: 'Miner', desc: 'We need to go deeper!', img: 'static/posters/resource_lab.png' }
                ]
            }
        },
        methods: {
            customLabel ({ title, desc }) {
                return `${title} – ${desc}`
            }
            // updateValue: function (value) {
            //     this.$emit('input', value)
            // },

        }
    }
</script>

