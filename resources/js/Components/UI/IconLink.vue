<template>
    <inertia-link
        class="inline-flex items-center justify-center bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150">
        <slot/>
    </inertia-link>
</template>

<script>
export default {
    name: "IconLink"
}
</script>

<style scoped>

</style>
