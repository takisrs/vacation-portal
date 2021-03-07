<template>
    <div class="box">

        <h1 class="box__title">Applications</h1>

        <router-link class="box__btn btn" to="/applications/create">Submit request</router-link>

        <div class="box__content">

            <table class="table">
                <thead>
                    <tr>
                        <th>Date submitted</th>
                        <th>Dates requested</th>
                        <th>Days requested</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(application, index) in applications" :key="index">
                        <td v-date:time>{{ application.createdAt }}</td>
                        <td>
                            <span v-date>{{ application.dateFrom }}</span> - 
                            <span v-date>{{ application.dateTo }}</span>
                        </td>
                        <td>{{ application.days }}</td>
                        <td>{{ statuses[application.status] }}</td>
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
            applications: {},
            statuses: ["pending", "approved", "rejected"]
        }
    },
    computed: {
        token () {
            return this.$store.getters.token;
        } 
    },
    created() {
        fetch(process.env.VUE_APP_ENDPOINT + "/applications", {
            headers: {
                'Authorization': 'Bearer ' + this.token
            }
        }).then(response => {
            //if (response.ok && response.status == 200)
            return response.json();
        }).then(body => {
            if (body.ok) {
                this.applications = body.data.applications;
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