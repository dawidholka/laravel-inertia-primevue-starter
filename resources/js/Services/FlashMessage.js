const FlashMessage = {
    methods: {
        flashSuccess(message) {
            this.$toast.add({
                severity: 'success',
                summary: 'Sukces',
                detail: message,
                life: 3000
            });
        },
        flashError(message) {
            this.$toast.add({
                severity: 'error',
                summary: 'Błąd',
                detail: message,
                life: 3000
            });
        }
    }
};

export default FlashMessage;
