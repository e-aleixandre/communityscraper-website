<template>
    <div v-show="message">
        <p class="text-red-500 text-xs italic">
            {{ message }}
        </p>
    </div>
</template>

<script>
    export default {
        props: ['message']
    }
</script>
