<template>
    <div :class="[ bgClass, borderClass ]" class="border-l-4 text-white-700 p-4">
        <p class="font-bold">{{ title }}</p>
        <p class="text-sm">
            <slot />
        </p>
    </div>
</template>

<script>
export default {
    name: "DashboardNotification",

    props: {
        type: String
    },

    data() {
        return {
            bgClass: '',
            borderClass: '',
            title: ''
        }
    },

    created() {
            switch (this.type)
            {
                case 'danger':
                    this.bgClass = `bg-red-100`;
                    this.borderClass = `border-red-500`;
                    this.title = 'Error';
                    break;
                case 'success':
                    this.bgClass = `bg-green-100`;
                    this.borderClass = `border-green-500`;
                    this.title = 'Éxito';
                    break;
                case 'warning':
                    this.bgClass = `bg-yellow-100`;
                    this.borderClass = `border-yellow-500`;
                    this.title = 'Alerta';
                    break;
                case 'info':
                    this.bgClass = `bg-blue-100`;
                    this.borderClass = `border-blue-500`;
                    this.title = 'Información';
                    break;
            }
    }
}
</script>
