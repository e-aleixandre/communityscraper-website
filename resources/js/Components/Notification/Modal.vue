<template>
        <div
            class="min-w-screen h-screen fixed left-0 top-0 flex justify-center items-center inset-0 z-50 outline-none focus:outline-none bg-no-repeat bg-center bg-cover">
            <div @click="$emit('close')" class="absolute bg-black opacity-80 inset-0 z-0"></div>

            <div class="w-full  max-w-lg p-5 relative mx-auto my-auto rounded-xl shadow-lg  bg-white ">
                <!--content-->
                <div class="">
                    <!--body-->
                    <div class="text-center p-5 flex-auto justify-center">
                        <slot name="icon"/>
                        <h2 class="text-xl font-bold py-4 ">
                            <slot name="title"/>
                        </h2>
                        <p class="text-sm text-gray-500 px-8">
                            <slot name="content"/>
                        </p>
                    </div>
                    <!--footer-->
                    <div class="p-3  mt-2 text-center space-x-4 md:block">
                        <slot name="actions"/>
                        <my-button @click="$emit('close')" v-if="cancel">
                            Cancelar
                        </my-button>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
import MyButton from "@/Components/UI/MyButton";

export default {
    name: "Modal",

    components: {MyButton},

    props: {
        cancel: {
            type: Boolean,
            default: true
        }
    }
}
</script>

<style scoped>
.bounce-enter-active {
    @apply animate-bounce;
}
</style>
