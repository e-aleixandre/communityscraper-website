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
            let color;
            let title;

            switch (this.type)
            {
                case 'danger':
                    color = 'red';
                    title = 'Error';
                    break;
                case 'success':
                    color = 'green';
                    title = 'Éxito';
                    break;
                case 'warning':
                    color = 'yellow';
                    title = 'Alerta';
                    break;
                case 'info':
                    color = 'blue';
                    title = 'Información';
                    break;
            }

            this.bgClass = `bg-${color}-100`;
            this.borderClass = `border-${color}-500`;
            this.title = title;
    }
}
</script>
