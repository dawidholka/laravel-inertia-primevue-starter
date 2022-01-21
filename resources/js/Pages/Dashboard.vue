<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <h5>{{ $t('common.emptyPage') }}</h5>
                <p>Use this page to start from scratch and place your custom content.</p>
            </div>
        </div>
    </div>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue'

export default {
    layout: AppLayout
};
</script>
