<template>
    <h4 :class="$style.component">
        Cargando...
    </h4>
</template>

<script>
export default {
    name: 'Loading',
}
</script>

<style module>
.component {
    background: url('../../../img/loading.gif') no-repeat left center;
    padding: 0 0 4px 50px;
}
</style>
