<template>
    <card class="card">
        <template #title>
            Browser Sessions
        </template>

        <template #content>
            <div class="mb-3">
                Manage and log out your active sessions on other browsers and devices.
            </div>

            <div class="max-w-xl text-sm text-gray-600">
                If necessary, you may log out of all of your other browser sessions across all of your devices. Some of your recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has been compromised, you should also update your password.
            </div>

            <!-- Other Browser Sessions -->
            <div class="mt-5 space-y-6" v-if="sessions.length > 0">
                <div class="flex items-center" v-for="(session, i) in sessions" :key="i">
                    <div>
                        <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             viewBox="0 0 24 24" stroke="currentColor" class="w-2rem h-2rem text-gray-500"
                             v-if="session.agent.is_desktop">
                            <path
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2"
                             stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"
                             class="w-8 h-8 text-gray-500" v-else>
                            <path d="M0 0h24v24H0z" stroke="none"></path>
                            <rect x="7" y="4" width="10" height="16" rx="1"></rect>
                            <path d="M11 5h2M12 17v.01"></path>
                        </svg>
                    </div>

                    <div class="ml-3">
                        <div class="text-sm text-gray-600">
                            {{ session.agent.platform }} - {{ session.agent.browser }}
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">
                                {{ session.ip_address }},

                                <span class="text-green-500 font-semibold"
                                      v-if="session.is_current_device">This Device</span>
                                <span v-else>Ostatnia aktywność {{ session.last_active }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Log Out Other Devices Confirmation Modal -->
        </template>

        <template #footer>
            <div class="flex items-center mt-5">
                <Button
                    @click="confirmLogout"
                    label="Wyloguj się z innych sesji"
                />
            </div>
        </template>

        <Dialog
            :visible="confirmingLogout"
            :closable="false"
            :modal="true"
            header="Wyloguj się z innych sesji"
            @close="closeModal"
        >

            Wprowadź hasło, aby potwierdzić, że chcesz wylogować się z innych sesji przeglądarki na wszystkich swoich urządzeniach.

            <div class="field">
                <label for="password">Hasło</label>
                <InputText
                    id="password"
                    class="w-full"
                    placeholder="Wprowadź hasło"
                    :class="{'p-invalid': form.errors.password}"
                    type="password"
                    v-model="form.password"
                />
                <small v-if="form.errors.password" class="p-invalid">
                    {{ form.errors.password }}
                </small>
            </div>

            <template #footer>
                <Button
                    label="Anuluj"
                    icon="pi pi-times"
                    class="p-button-text"
                    @click="closeModal"
                />
                <Button
                    label="Wyloguj"
                    icon="pi pi-check"
                    class="p-button-text"
                    :loading="form.processing"
                    @click="logoutOtherBrowserSessions"
                />
            </template>
        </Dialog>
    </card>
</template>

<script>
import Card from "primevue/card";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import FlashMessage from "../../../Services/FlashMessage";

export default {
    props: ['sessions'],

    components: {
        Card,
        Button,
        Dialog,
        InputText,
    },
    mixins: [FlashMessage],
    data() {
        return {
            confirmingLogout: false,

            form: this.$inertia.form({
                password: '',
            })
        }
    },

    methods: {
        confirmLogout() {
            this.confirmingLogout = true

            setTimeout(() => this.$refs.password.focus(), 250)
        },

        logoutOtherBrowserSessions() {
            this.form.delete(route('other-browser-sessions.destroy'), {
                preserveScroll: true,
                onSuccess: () => {
                    this.closeModal();
                    this.flashSuccess('Wylogowano z innych sesji.')
                },
                onFinish: () => this.form.reset(),
            })
        },

        closeModal() {
            this.confirmingLogout = false
            this.form.clearErrors();
            this.form.reset()
        },
    },
}
</script>
