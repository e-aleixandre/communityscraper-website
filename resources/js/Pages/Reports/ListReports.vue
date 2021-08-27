<template>
    <authenticated-layout>
        <Head title="Lista de informes" />
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Lista de informes
            </h2>
        </template>
        <modal v-if="deleteModal.show" @close="deleteModal.show = false">
            <template #icon>
                <trash-icon class="w-16 h-16 text-red-500 mx-auto"/>
            </template>
            <template #title>
                ¿Quieres borrar el informe?
            </template>
            <template #content>
                Una vez borrado no será posible recuperar la información. El informe será borrado y tendrá que generarse
                de nuevo.
            </template>
            <template #actions>
                <my-button class="bg-red-500 hover:bg-red-600" :disabled="deleteModal.processing"
                           @click="deleteReport(deleteModal.reportId)">
                    Borrar
                </my-button>
            </template>
        </modal>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <template v-if="reports.length === 0">
                            <dashboard-notification type="warning">
                                No hay informes para mostrar. Si no hay informes generados puedes ir a
                                <inertia-link :href="route('reports.create')">generar uno</inertia-link>
                                . Si
                                crees que es un error avisa al administrador.
                            </dashboard-notification>
                        </template>
                        <template v-else>
                            <table class="min-w-max w-full table-auto">
                                <thead>
                                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-center">Fecha inicial</th>
                                    <th class="py-3 px-6 text-center">Fecha final</th>
                                    <th class="py-3 px-6 text-center">Estado</th>
                                    <th class="py-3 px-6 text-center">Acciones</th>
                                </tr>
                                </thead>
                                <tbody class="text-gray-600 text-sm font-light">
                                <transition-group name="fade">
                                    <tr v-for="(report, index) in reports" :key="report.id"
                                        :class="{'bg-gray-50': index % 2}"
                                        @click=""
                                        class="border-b border-gray-200 hover:bg-gray-100 cursor-pointer">
                                        <!-- Fecha inicial -->

                                        <td class="py-3 px-6 text-center whitespace-nowrap">
                                            {{
                                                minDates[report.id]
                                            }}
                                        </td>
                                        <!-- Fecha inicial -->

                                        <td class="py-3 px-6 text-center whitespace-nowrap">
                                            {{
                                                maxDates[report.id]
                                            }}
                                        </td>
                                        <!-- Estado -->

                                        <td class="py-3 px-6 text-center whitespace-nowrap">
                                            <div :class="{'bg-indigo-500': !report.completed && !report.errored,
                                                           'bg-red-500': report.completed && !report.errored,
                                                           'bg-green-500': report.errored && !report.completed,
                                                           'bg-gray-700': report.errored && report.completed}"
                                                 class="rounded-full py-1 inline-block px-6 text-white text-xs w-32 text-center font-extrabold">
                                                <template v-if="!report.completed && !report.errored">
                                                    PENDIENTE
                                                </template>
                                                <template v-else-if="report.completed && !report.errored">
                                                    COMPLETADO
                                                </template>
                                                <template v-else-if="report.errored && !report.completed">
                                                    FALLIDO
                                                </template>
                                                <template v-else>
                                                    DETENIDO
                                                </template>
                                            </div>
                                        </td>
                                        <!-- Actions -->
                                        <td class="py-3 px-6 text-center">
                                            <div class="flex item-center justify-center">
                                                <!-- Action: download -->
                                                <a
                                                    :href="route('reports.download', report.id)"
                                                    v-if="report.completed && !report.errored"
                                                    class="w-5 mr-2 transform hover:text-purple-500 hover:scale-110 cursor-pointer">
                                                    <download-icon />
                                                </a>
                                                <!-- Action: stop -->
                                                <div
                                                    v-if="!report.completed && !report.errored"
                                                    class="w-5 mr-2 transform hover:text-purple-500 hover:scale-110 cursor-pointer">
                                                    <stop-icon @click.stop="stopReport(report.id)"/>
                                                </div>
                                                <!-- Action: restart -->
                                                <div
                                                    v-if="report.errored"
                                                    class="w-5 mr-2 transform hover:text-purple-500 hover:scale-110 cursor-pointer">
                                                    <refresh-icon @click.stop="restartReport(report.id)"/>
                                                </div>
                                                <!-- Action: delete -->
                                                <div
                                                    v-if="report.completed || report.errored"
                                                    class="w-5 mr-2 transform hover:text-purple-500 hover:scale-110 cursor-pointer">
                                                    <trash-icon @click.stop="showDeleteModal(report.id)"/>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                </transition-group>
                                </tbody>
                            </table>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </authenticated-layout>
</template>

<script>

import AuthenticatedLayout from "@/Layouts/Authenticated";
import DashboardNotification from "@/Components/Notification/DashboardNotification";
import TrashIcon from "@/Components/Icon/TrashIcon";
import Modal from "@/Components/Notification/Modal";
import MyButton from "@/Components/UI/MyButton";
import {Inertia} from "@inertiajs/inertia";
import { Head } from '@inertiajs/inertia-vue3';
import RefreshIcon from "@/Components/Icon/RefreshIcon";
import {InertiaLink} from "@inertiajs/inertia-vue3";
import StopIcon from "@/Components/Icon/StopIcon";
import DownloadIcon from "@/Components/Icon/DownloadIcon";

export default {
    name: "ListReports",

    components: {
        DownloadIcon,
        StopIcon,
        RefreshIcon,
        MyButton,
        Modal,
        TrashIcon,
        DashboardNotification,
        AuthenticatedLayout,
        InertiaLink,
        Head
    },

    props: {
        reports: Object
    },

    data() {
        return {
            deleteModal: {
                show: false,
                processing: false,
                clientId: null
            }
        }
    },

    methods: {

        showDeleteModal(reportId) {
            this.deleteModal.reportId = reportId;
            this.deleteModal.show = true;
        },
        stopReport(reportId) {
          Inertia.post(this.route('reports.stop', reportId));
        },
        restartReport(reportId) {
          Inertia.post(this.route('reports.restart', reportId));
        },
        deleteReport(reportId) {
            Inertia.delete(this.route('reports.destroy', reportId), {
                onStart: () => {
                  this.deleteModal.processing = true;
                },
                onFinish: () => {
                    this.deleteModal.show = false;
                    this.deleteModal.processing = false;
                }
            });
        }
    },

    computed: {
        minDates() {
            return this.reports.reduce((acc, report) => {
                acc[report.id] = new Date(report.min_date).toLocaleDateString('es-ES', {
                    day: "2-digit",
                    month: "2-digit",
                    year: "numeric",
                    hour: "2-digit",
                    minute: "2-digit"
                });
                return acc;
            }, {});
        },
        maxDates() {
            return this.reports.reduce((acc, report) => {
                acc[report.id] = new Date(report.max_date).toLocaleDateString('es-ES', {
                    day: "2-digit",
                    month: "2-digit",
                    year: "numeric",
                    hour: "2-digit",
                    minute: "2-digit"
                });
                return acc;
            }, {});
        }
    }
}
</script>
<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: opacity .5s
}

.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */
{
    opacity: 0
}
</style>
