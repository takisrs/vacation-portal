<template>
    <div class="box">
        <h1 class="box__title">Users</h1>

        <router-link class="box__btn btn" to="/users/create">Create a user</router-link>

        <div class="box__content">

            <table class="table">
                <thead>
                    <tr>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(user, index) in users" :key="index">
                        <td><router-link :to="'/users/edit/'+user.id">{{ user.firstName }}</router-link></td>
                        <td>{{ user.lastName }}</td>
                        <td>{{ user.email }}</td>
                        <td>{{ userTypes[user.type - 1] }}</td>
                        <td><router-link tag="a" class="btn btn--sm" :to="'/users/edit/'+user.id">edit</router-link></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            users: {},
            userTypes: ["user", "admin"]
        }
    },
    computed: {
        token () {
            return this.$store.getters.token;
        } 
    },
    created() {
        fetch(process.env.VUE_APP_ENDPOINT + "/users", {
            headers: {
                'Authorization': 'Bearer ' + this.token
            }
        }).then(response => {
            //if (response.ok && response.status == 200)
            return response.json();
        }).then(body => {
            if (body.ok) {
                this.users = body.data.users;
            } else {
                this.$store.commit('setMessage', {
                    class: "warning", 
                    message: body.message
                });
            }
        }).catch(error => {
            console.log(error);
        });
    }
}
</script>