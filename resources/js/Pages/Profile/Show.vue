<template>
    <app-layout>
        <div class="mb-5">
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div v-if="$page.props.jetstream.canUpdateProfileInformation">
                    <update-profile-information-form :user="$page.props.user" class="mb-5"/>
                </div>

                <div v-if="$page.props.jetstream.canUpdatePassword">
                    <update-password-form class="mb-5"/>
                </div>

                <logout-other-browser-sessions-form :sessions="sessions" class="mt-10 sm:mt-0"/>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue'
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue'
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue'
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue'

export default {
    props: ['sessions'],

    components: {
        AppLayout,
        LogoutOtherBrowserSessionsForm,
        UpdatePasswordForm,
        UpdateProfileInformationForm,
    },
}
</script>
