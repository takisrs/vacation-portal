<template>
    <div class="box box--sm">
        <div class="box__title">Login</div>
        <div class="box__content">
            <form method="POST" class="form">

                <div class="form__group">
                    <label for="email" class="form__label">Email</label>
                    <input type="text" class="form__input" id="email" name="email" placeholder="Email..." v-model="email">
                </div>

                <div class="form__group">
                    <label for="password" class="form__label">Password</label>
                    <input type="password" class="form__input" id="password" name="password" placeholder="Password..." v-model="password">
                </div>

                <input type="submit" class="btn form__action form__action--right" @click.prevent="login()" value="Submit"/>

            </form>
        </div>
    </div>
</template>

<script>
import validationMixin from '@/mixins/validation.js';

export default {
    data() {
        return {
            email: "",
            password: ""
        }
    },
    methods: {
        login(){
            if (this.validate([
                { field: 'email', value: this.email, rules: ['required', 'email'] },
                { field: 'password', value: this.password, rules: ['required'] },
            ])){
                this.$store.dispatch('login', {
                    email: this.email,
                    password: this.password
                });
            }
            
        }
    },
    mixins: [
        validationMixin
    ]
}
</script>