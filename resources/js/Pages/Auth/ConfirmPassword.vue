<template>
    <Head title="Confirm Password" />

    <div class="mb-4 text-sm text-gray-600">
        Esta es una zona protegida de la aplicación. Por favor, confirma tu contraseña para continuar.
    </div>

    <BreezeValidationErrors class="mb-4" />

    <form @submit.prevent="submit">
        <div>
            <BreezeLabel for="password" value="Contraseña" />
            <BreezeInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="current-password" autofocus />
        </div>

        <div class="flex justify-end mt-4">
            <BreezeButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Confirmar
            </BreezeButton>
        </div>
    </form>
</template>

<script>
import BreezeButton from '@/Components/UI/MyButton.vue'
import BreezeGuestLayout from '@/Layouts/Guest.vue'
import BreezeInput from '@/Components/Form/Input.vue'
import BreezeLabel from '@/Components/Form/Label.vue'
import BreezeValidationErrors from '@/Components/Form/ValidationErrors.vue'
import { Head } from '@inertiajs/inertia-vue3';

export default {
    layout: BreezeGuestLayout,

    components: {
        BreezeButton,
        BreezeInput,
        BreezeLabel,
        BreezeValidationErrors,
        Head,
    },

    data() {
        return {
            form: this.$inertia.form({
                password: '',
            })
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('password.confirm'), {
                onFinish: () => this.form.reset(),
            })
        }
    }
}
</script>
