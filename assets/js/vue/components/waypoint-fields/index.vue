<template>
    <div>
            <waypoint-form-fields
                :key="item['@id']"
                :item="item"
            />
    </div>
</template>

<script>
import WaypointFormFields from '@/vue/components/waypoint-fields/waypoint-form-fields'
// import {translate} from '@/vue/services/translation-service'

export default {
    name: 'WaypointFields',
    components: {
        WaypointFormFields,
    },
    props: {
        // loading: {
        //     type: Boolean,
        //     required: true,
        // },
        type: {
            type: String,
            required: true,
        },
        item: {
            type: Object,
            required: true,
        },
    },
    // methods: {
    //     translate(key) {
    //         return translate(key)
    //     },
    // },
}
</script>
