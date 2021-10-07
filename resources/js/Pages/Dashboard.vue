<template>
    <app-layout>
        <div class="grid">
            <div class="col-12">
                <div class="card">
                    <h5>Empty Page</h5>
                    <p>Use this page to start from scratch and place your custom content.</p>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import {defineComponent} from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

export default defineComponent({
    components: {
        AppLayout,
    },
})
</script>
