const EMAIL_REGEX = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

export default {
    data() {
        return {

        }
    },
    methods: {
        validate(toValidate){
            let valid = true;
            if (toValidate.length > 0){
                for (let i = 0; i < toValidate.length; i++){
                    toValidate[i].rules.forEach(rule => {
                        valid = this.check(rule, toValidate[i]) && valid;
                    });
                }
            }

            return valid;

        },
        check(rule, data){
            if (rule == 'required'){
                if (data.value == ''){
                    this.$store.commit('setMessage', {
                        class:"error", 
                        message:"The " + data.field + " field is required"
                    });
                    return false;
                }
            } else if (rule == 'email'){
                if (!EMAIL_REGEX.test(String(data.value).toLowerCase())){
                    this.$store.commit('setMessage', {
                        class:"error", 
                        message:"Invalid Email"
                    });
                    return false;
                }
            } else if (rule == 'match'){
                if (data.value != data.match){
                    this.$store.commit('setMessage', {
                        class:"error", 
                        message:"Passwords do not match"
                    });
                    return false;
                }
            }
            return true;
        }
    }
}