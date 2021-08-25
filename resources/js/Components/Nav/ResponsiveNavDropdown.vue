<template>
    <div>
        <button @click="open = !open" class="inline-flex items-center" :class="[classes, openClasses]">
            <slot name="category" />
            <expand-icon/>
        </button>
        <div v-show="open" class="resp-nav-dropdown-wrapper">
            <slot name="links" />
        </div>
    </div>
</template>

<script>
import ExpandIcon from "@/Components/Icon/ExpandIcon";
export default {
    components: {ExpandIcon},
    props: ['active'],

    data() {
      return {
          open: false
      }
    },

    computed: {
        classes() {
            return this.active
                ? 'text-left w-full block pl-3 pr-4 py-2 border-l-4 border-indigo-400 text-base font-medium text-indigo-700 bg-indigo-50 transition duration-150 ease-in-out'
                : 'text-left w-full block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 transition duration-150 ease-in-out'
        },
        openClasses() {
            if (this.open && this.active)
            {
                return 'outline-none text-indigo-800 bg-indigo-100 border-indigo-700';
            }
            else if (this.active) // active && !open
            {
                return 'outline-none text-indigo-800 bg-indigo-50 border-indigo-700';
            }
            else if (this.open) // open && !active
            {
                return 'outline-none text-gray-800 bg-gray-50 border-gray-300';
            }
            else // !active && !open
            {
                return '';
            }
        }
    }
}
</script>
<style>
.resp-nav-dropdown-wrapper a {
    padding-left: 1.75rem;
}
</style>
