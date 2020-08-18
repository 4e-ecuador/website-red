<template>
    <div>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroupPrepend">
                    <span v-if="intelLinkStatus==='valid'" class="text-success">
                        <span class="oi oi-check"></span>
                    </span>
                    <span v-else-if="intelLinkStatus==='invalid'" class="text-danger">
                        <span class="oi oi-x"></span>
                    </span>
                </span>
            </div>
            <input type="text" class="form-control invalid" placeholder="Intel link"
                   @input="intelLink = $event.target.value;onInput()"
            >
        </div>
        <span v-if="intelLinkStatus==='valid'" class="text-muted">
                Lat: {{ lat }} - Lon: {{ lon }}
        </span>
        <span v-else-if="intelLinkStatus==='invalid'" class="text-danger">
            Invalid intel link!
        </span>
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
        intelLinkStatus: '',
        lat: 0,
        lon: 0,
    }),
    methods: {
        // https://intel.ingress.com/?pll=0.871806,-79.852543
        onInput() {
            // console.log(this.intelLink)
            this.lat = 0
            this.lon = 0
            this.intelLinkStatus = ''
            if (this.intelLink) {
                const matches = this.intelLink.match(/intel.ingress.com\/\?pll=(-?\d+.\d+),(-?\d+.\d+)/)
                if (matches) {
                    this.lat = matches[1]
                    this.lon = matches[2]
                    this.intelLinkStatus = 'valid'
                } else {
                    this.intelLinkStatus = 'invalid'
                }
            }
            $('#waypoint_lat').val(this.lat)
            $('#waypoint_lon').val(this.lon)
        },
    },
}
</script>
