<template>
    <div>
        <div>Lat: {{lat}}</div>
        <div>Lon: {{lon}}</div>

        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroupPrepend">
                            <span v-if="intelLinkValid===1" class="text-success"><span class="oi oi-check"></span></span>
        <span v-else-if="intelLinkValid===2" class="text-danger"><span class="oi oi-x"></span></span>

                </span>
            </div>
            <input type="text" class="form-control invalid" placeholder="Intel link"
                   @input="intelLink = $event.target.value;onInput()"
            >
        </div>
    </div>
</template>

<script>
import $ from 'jquery'

export default {
    name: 'WaypointFormFields',
    props: {
        item: {
            type: Object,
            required: true,
        },
    },
    data: () => ({
        loading: false,
        intelLink: '',
        intelLinkValid: 0,
        lat: 0,
        lon: 0,
    }),
    methods: {
        onInput() {
            console.log(this.intelLink)
            if (!this.intelLink) {
                this.intelLinkValid = 0
                return
            }
            //https://intel.ingress.com/?pll=-0.74746,-90.312896
            const matches = this.intelLink.match(/intel.ingress.com\/\?pll=([\-]\d+[.]\d+),([\-]\d+[.]\d+)/)
            // console.log(matches)
            // console.log(matches[1])
            // console.log(matches[2])
            if (matches) {
                this.lat = matches[1]
                this.lon = matches[2]
                $('#waypoint_lat').val(this.lat)
                $('#waypoint_lon').val(this.lon)
                this.intelLinkValid = 1
            } else {
                this.lat = 0
                this.lon = 0
                this.intelLinkValid = 2
            }
        },
    },
}
</script>
