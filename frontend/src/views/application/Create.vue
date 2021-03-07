<template>
    <div class="box box--sm">
        <h1 class="box__title">Submit request</h1>

        <div class="box__content">

            <form class="form" method="POST">
                <div class="form__group">
                    <label for="dateFrom" class="form__label">Date from</label>
                    <input type="date" id="dateFrom" class="form__input" name="dateFrom" placeholder="Date from..." v-model="dateFrom">
                </div>
                <div class="form__group">
                    <label for="dateTo" class="form__label">Date to</label>
                    <input type="date" id="dateTo" class="form__input" name="dateTo" placeholder="Date to..." v-model="dateTo">
                </div>
                <div class="form__group">
                    <label for="reason" class="form__label">Reason</label>
                    <textarea id="reason" class="form__input" name="reason" placeholder="Reason..." v-model="reason"></textarea>
                </div>
                <div class="form__group">
                    <input type="submit" class="btn form__action form__action--right" @click.prevent="createApplication()" value="Submit"/>
                </div>
            </form>

        </div>

    </div>

</template>

<script>
import validationMixin from '@/mixins/validation.js';

export default {
    data(){
        return {
            dateFrom: "",
            dateTo: "",
            reason: ""
        }
    },
    methods: {
        createApplication() {
            if (this.validate([
                { field: 'dateFrom', value: this.dateFrom, rules: ['required'] },
                { field: 'dateTo', value: this.dateTo, rules: ['required'] },
                { field: 'reason', value: this.reason, rules: ['required'] },
            ])){
                this.$store.dispatch("createApplication", {
                    dateFrom: this.dateFrom,
                    dateTo: this.dateTo,
                    reason: this.reason
                });
            }
        }
    },
    mixins: [
        validationMixin
    ]
}
</script>