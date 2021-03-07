<template>
    <div class="box box--sm">
        <h1 class="box__title">Create a user</h1>

        <div class="box__content">

            <form class="form" method="POST">
                <div class="form__group">
                    <label for="firstName" class="form__label">First name</label>
                    <input type="text" id="firstName" class="form__input" name="firstName" placeholder="First name..." v-model="firstName">
                </div>
                <div class="form__group">
                    <label for="lastName" class="form__label">Last name</label>
                    <input type="text" id="lastName" class="form__input" name="lastName" placeholder="Last name..." v-model="lastName">
                </div>
                <div class="form__group">
                    <label for="email" class="form__label">Email</label>
                    <input id="email" class="form__input" name="email" placeholder="Email..." v-model="email">
                </div>
                <div class="form__group">
                    <label for="password" class="form__label">Password</label>
                    <input id="password" class="form__input" name="password" placeholder="Password..." v-model="password">
                </div>
                <div class="form__group">
                    <label for="confirmPassword" class="form__label">Confirm password</label>
                    <input id="confirmPassword" class="form__input" name="confirmPassword" placeholder="Confirm password..." v-model="confirmPassword">
                </div>
                <div class="form__group">
                    <label for="userType" class="form__label">User type</label>
                    <select id="userType" name="userType" class="form__select" v-model="userType">
                        <option value="" disabled selected>Select user type...</option>
                        <option value="1">User</option>
                        <option value="2">Admin</option>
                    </select>
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
            firstName: "",
            lastName: "",
            email: "",
            password: "",
            confirmPassword: "",
            userType: ""
        }
    },
    methods: {
        createApplication() {
            if (this.validate([
                { field: 'firstName', value: this.firstName, rules: ['required'] },
                { field: 'lastName', value: this.lastName, rules: ['required'] },
                { field: 'email', value: this.email, rules: ['required'] },
                { field: 'password', value: this.password, rules: ['required'] },
                { field: 'confirmPassword', value: this.confirmPassword, rules: ['required'] },
                { field: 'userType', value: this.userType, rules: ['required'] },
            ])){
                this.$store.dispatch("createUser", {
                    firstName: this.firstName,
                    lastName: this.lastName,
                    email: this.email,
                    password: this.password,
                    type: this.userType
                });
            }
        }
    },
    mixins: [
        validationMixin
    ]
}
</script>