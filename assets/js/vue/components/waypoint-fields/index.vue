<template>
    <div class="card-body alert-success">
        <h3>Helper</h3>

        <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                Intel Link
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
                ...
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">
                ...
            </a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <waypoint-form-fields
                :key="item['@id']"
                :item="item"
            />
        </div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">...</div>
        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
    </div>
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
