<template>
    <authenticated-layout>
        <Head title="Generar informe"/>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Nuevo informe
            </h2>
        </template>

        <dashboard-container>
            <template v-if="!created">
                <dashboard-notification class="mb-4" :type="flash.type" v-if="errored" type="danger">
                    {{ flash.message }}
                </dashboard-notification>
                <form novalidate class="w-full md:w-5/12" @submit.prevent="submit">
                    <div class="flex justify-between flex-wrap mb-4">
                        <div class="mb-4">
                            <input-label for="min_date">Fecha inicial</input-label>
                            <input-field id="min_date" type="date" :class="{ 'border-red-500': errors.min_date }"
                                         class="mt-1 block w-full" v-model="form.min_date" required/>
                            <input-error class="mt-1" :message="errors.min_date"/>
                        </div>
                        <div class="mb-4">
                            <input-label for="min_time">Hora inicial</input-label>
                            <input-field id="min_time" type="time" :class="{ 'border-red-500': errors.min_time }"
                                         class="mt-1 block w-24" v-model="form.min_time" required/>
                            <input-error class="mt-1" :message="errors.min_time"/>
                        </div>
                    </div>
                    <div class="flex justify-between flex-wrap mb-4">
                        <div class="mb-4">
                            <input-label for="max_date">Fecha final</input-label>
                            <input-field id="max_date" type="date" :class="{ 'border-red-500': errors.max_date }"
                                         class="mt-1 block w-full" v-model="form.max_date" required/>
                            <input-error class="mt-1" :message="errors.max_date"/>
                        </div>
                        <div class="mb-4">
                            <input-label for="max_time">Hora final</input-label>
                            <input-field id="max_time" type="time" :class="{ 'border-red-500': errors.max_time }"
                                         class="mt-1 block w-24" v-model="form.max_time" required/>
                            <input-error class="mt-1" :message="errors.max_time"/>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="flex items-center">
                            <form-checkbox name="notify" v-model:checked="form.notify"/>
                            <span
                                class="ml-2 text-sm text-gray-600">Notificar por email cuando se genere el informe</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <form-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Generar informe
                        </form-button>
                    </div>
                </form>
            </template>
            <template v-else>
                <dashboard-notification class="mb-4" type="success">
                    El informe ha empezado a generarse. Si lo has solicitado recibirás un email cuando esté finalizado.
                </dashboard-notification>
                <button-link :href="route('reports.index')">Ver informes</button-link>
            </template>
        </dashboard-container>
    </authenticated-layout>
</template>

<script>

import AuthenticatedLayout from "@/Layouts/Authenticated";
import DashboardContainer from "@/Components/UI/DashboardContainer";
import InputLabel from "@/Components/Form/Label";
import InputField from "@/Components/Form/Input";
import InputError from "@/Components/Form/InputError";
import FormButton from "@/Components/UI/MyButton";
import FormCheckbox from "@/Components/Form/Checkbox";
import DashboardNotification from "@/Components/Notification/DashboardNotification";
import ButtonLink from "@/Components/UI/ButtonLink";
import { Head } from '@inertiajs/inertia-vue3';

export default {
    props: {
        errors: Object,
        ok: {
            type: Boolean,
            default: false
        },
        flash: {
            type: Object,
            default: {
                message: '',
                type: ''
            }
        }
    },

    data: function () {
        return {
            form: this.$inertia.form({
                min_date: '',
                min_time: '',
                max_date: '',
                max_time: '',
                notify: false
            }),
            created: false,
            errored: false
        }
    },

    components: {
        ButtonLink,
        DashboardNotification,
        DashboardContainer,
        AuthenticatedLayout,
        InputLabel,
        InputField,
        InputError,
        FormButton,
        FormCheckbox,
        Head
    },

    methods: {
        submit() {
            this.form.post(this.route('reports.store'), {
                onSuccess: () => {
                    // If creation failed, then it errored
                    this.created = this.ok;
                    this.errored = !this.created;

                    // If it didnt fail, reset the form
                    if (this.created) {
                        this.form.reset();
                    }
                }
            });
        }
    }
}
</script>
