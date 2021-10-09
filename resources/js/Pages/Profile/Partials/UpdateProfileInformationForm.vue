<template>
    <Card class="card" @submitted="updateProfileInformation">
        <template #title>
            Twój profil
        </template>

        <template #content>
            <div class="mb-5">
                Zaktualizuj informacje profilowe i adres email swojego konta.
            </div>
            <!-- Name -->
            <div class="field">
                <label for="name">Nazwa</label>
                <InputText
                    id="name"
                    class="w-full"
                    :class="{'p-invalid': form.errors.name}"
                    type="text"
                    v-model="form.name"
                />
                <small v-if="form.errors.name" class="p-invalid">
                    {{ form.errors.name }}
                </small>
            </div>
            <div class="field">
                <label for="email">Adres e-mail</label>
                <InputText
                    id="email"
                    class="w-full"
                    :class="{'p-invalid': form.errors.email}"
                    type="email"
                    v-model="form.email"
                />
                <small v-if="form.errors.email" class="p-invalid">
                    {{ form.errors.email }}
                </small>
            </div>
        </template>

        <template #footer>
            <Button
                label="Zapisz"
                :loading="form.processing"
                @click="updateProfileInformation"
            />
        </template>
    </Card>
</template>

<script>
import Card from "primevue/card";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import FlashMessage from "../../../Services/FlashMessage";

export default {
    components: {
        Card,
        InputText,
        Button,
    },

    props: ['user'],
    mixins: [FlashMessage],
    data() {
        return {
            form: this.$inertia.form({
                _method: 'PUT',
                name: this.user.name,
                email: this.user.email,
                photo: null,
            }),

            photoPreview: null,
        }
    },

    methods: {
        updateProfileInformation() {
            if (this.$refs.photo) {
                this.form.photo = this.$refs.photo.files[0]
            }

            this.form.post(route('user-profile-information.update'), {
                errorBag: 'updateProfileInformation',
                preserveScroll: true,
                onSuccess: () => {
                    this.flashSuccess('Zapisano dane konta.');
                }
            });
        },

        selectNewPhoto() {
            this.$refs.photo.click();
        },

        updatePhotoPreview() {
            const photo = this.$refs.photo.files[0];

            if (!photo) return;

            const reader = new FileReader();

            reader.onload = (e) => {
                this.photoPreview = e.target.result;
            };

            reader.readAsDataURL(photo);
        },

        deletePhoto() {
            this.$inertia.delete(route('current-user-photo.destroy'), {
                preserveScroll: true,
                onSuccess: () => {
                    this.photoPreview = null;
                    this.clearPhotoFileInput();
                },
            });
        },

        clearPhotoFileInput() {
            if (this.$refs.photo?.value) {
                this.$refs.photo.value = null;
            }
        },
    },
}
</script>
