<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex-shrink-0 flex items-center">
                                <inertia-link :href="route('dashboard')">
                                    <breeze-application-logo class="w-40 h-10" />
                                </inertia-link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex relative">
                                <breeze-nav-link :href="route('dashboard')" :active="route().current('dashboard')">
                                    Mi Panel
                                </breeze-nav-link>
                                <nav-dropdown :active="$page.component.startsWith('Report')">
                                    <template #trigger>
                                        Informes
                                    </template>
                                    <template #content>
                                        <breeze-dropdown-link :href="route('reports.index')" as="button" :active="route().current('reports.index')">
                                            Ver informes
                                        </breeze-dropdown-link>
                                        <breeze-dropdown-link :href="route('reports.create')" as="button" :active="route().current('reports.create')">
                                            Nuevo informe
                                        </breeze-dropdown-link>
                                    </template>
                                </nav-dropdown>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <breeze-dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                {{ $page.props.auth.user.name }}

                                                <expand-icon class="w-3 h-3"/>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <breeze-dropdown-link :href="route('logout')" method="post" as="button">
                                            Salir
                                        </breeze-dropdown-link>
                                    </template>
                                </breeze-dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button @click="showingNavigationDropdown = ! showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <breeze-responsive-nav-link :href="route('dashboard')" :active="route().current('dashboard')">
                            Mi Panel
                        </breeze-responsive-nav-link>
                        <responsive-nav-dropdown :active="$page.component.startsWith('Report')">
                            <template v-slot:category>
                                Informes
                            </template>
                            <template v-slot:links>
                                <breeze-responsive-nav-link href="#">
                                    Ver informes
                                </breeze-responsive-nav-link>
                                <breeze-responsive-nav-link href="#">
                                    Nuevo informe
                                </breeze-responsive-nav-link>
                            </template>
                        </responsive-nav-dropdown>

                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">{{ $page.props.auth.user.name }}</div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <breeze-responsive-nav-link :href="route('logout')" method="post" as="button">
                                Salir
                            </breeze-responsive-nav-link>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex items-baseline">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

<script>
import BreezeApplicationLogo from '@/Components/ApplicationLogo'
import BreezeDropdown from '@/Components/UI/Dropdown'
import BreezeDropdownLink from '@/Components/Nav/NavDropdownLink'
import BreezeNavLink from '@/Components/Nav/NavLink'
import BreezeResponsiveNavLink from '@/Components/Nav/ResponsiveNavLink';
import NavDropdown from "@/Components/Nav/NavDropdown";
import ExpandIcon from "@/Components/Icon/ExpandIcon";
import ResponsiveNavDropdown from "@/Components/Nav/ResponsiveNavDropdown";
import {InertiaLink} from "@inertiajs/inertia-vue3";

export default {
    components: {
        ResponsiveNavDropdown,
        ExpandIcon,
        NavDropdown,
        BreezeApplicationLogo,
        BreezeDropdown,
        BreezeDropdownLink,
        BreezeNavLink,
        BreezeResponsiveNavLink,
        InertiaLink
    },

    data() {
        return {
            showingNavigationDropdown: false,
        }
    },
}
</script>
