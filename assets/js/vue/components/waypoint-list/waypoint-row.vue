<template>
    <tr>
        <td>{{waypoint.id}}</td>
        <td>{{waypoint.name}}</td>
        <td>{{waypoint.city}}</td>
        <td>{{waypoint.category}}</td>
        <td><a :href="'/waypoint/'+waypoint.id+'/edit'"><span class="oi oi-pencil"></span></a></td>
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
