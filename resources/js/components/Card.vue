<template>
    <loading-card :loading="loading" class="metric px-6 py-4 relative">
        <div class="flex mb-4">
            <h3 class="mr-3 text-base text-80 font-bold">{{ card.name }}</h3>

        </div>

        <p class="flex items-center text-xl mb-4">
            <i v-for="n in card.max">
                <span v-if="n<=value">★</span>
                <span v-else>☆</span>
            </i>
            <span class="ml-6">
            {{ value }} / {{ card.max }}
                </span>
        </p>

    </loading-card>
</template>

<script>
export default {
    props: {
        card: {
            type: Object,
            required: true,
        },
        resourceName: {
            type: String,
            default: '',
        },
        resourceId: {
            type: [Number, String],
            default: '',
        },
        loading: {default: true},
        value: {default: 0}
    },

    mounted() {
        console.log(this.card);
        this.loadAverage();
    },

    methods: {
        loadAverage() {
            this.loading = true;
            Nova.request().get(this.metricEndpoint()).then(
                ({
                    data: {
                        value: [value]
                    }
                 }) => {
                    console.log(value);
                    this.value = value;
                    this.loading = false;
                })
        },

        metricEndpoint() {
            if (this.resourceName && this.resourceId) {
                return `/nova-api/${this.resourceName}/${this.resourceId}/metrics/${
                    this.card.uriKey
                    }`
            } else if (this.resourceName) {
                return `/nova-api/${this.resourceName}/metrics/${this.card.uriKey}`
            } else {
                return `/nova-api/metrics/${this.card.uriKey}`
            }
        },
    }
}
</script>
