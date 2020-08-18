<template>
    <tr>
        <td>
            <a :href="'/waypoint/'+waypoint.id">{{waypoint.name}}</a>
        </td>
        <td v-if="waypoint.city">
            {{waypoint.city}}
        </td>
        <td v-else>
            n/a
        </td>
        <td>
            <a :href="'/waypoint/'+waypoint.id+'/edit'"><span class="oi oi-pencil"></span></a>
        </td>
    </tr>
</template>

<script>
export default {
    name: 'WaypointForm',
    props: {
        waypoint: {
            type: Object,
            required: true,
        },
    },
}
</script>
