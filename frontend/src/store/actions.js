import router from '../router/index';

export default {

    setMessage({ commit }, message){
        commit("setMessage", message);
    },

    createUser({ state, commit }, payload){
        
        fetch(process.env.VUE_APP_ENDPOINT + '/users', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer '+state.auth.token
            },
            body: JSON.stringify({
                ...payload
            })
        }).then(response => {
            return response.json();
        }).then(data => {
            if (data.ok) {
                commit('setMessage', { message: data.message, class: 'success'});
            } else {
                commit('setMessage', { message: data.message, class: 'error'});
            }
            router.push('/users');
        }).catch(error => {
            commit('setMessage', { message: error.message, class: 'error'});
            router.push('/users');
        });
    },

    createApplication({ state, commit }, payload){
        
        fetch(process.env.VUE_APP_ENDPOINT + '/applications', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer '+state.auth.token
            },
            body: JSON.stringify({
                dateFrom: payload.dateFrom,
                dateTo: payload.dateTo,
                reason: payload.reason
            })
        }).then(response => {
            return response.json();
        }).then(data => {
            if (data.ok) {
                commit('setMessage', { message: data.message, class: 'success'});
            } else {
                commit('setMessage', { message: data.message, class: 'error'});
            }
            router.push('/applications');
        }).catch(error => {
            commit('setMessage', { message: error.message, class: 'error'});
            router.push('/applications');
        });
    },
    
    updateApplication({ state, commit }, payload){
        
        fetch(process.env.VUE_APP_ENDPOINT + '/applications/' + payload.id + '/' + payload.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer '+state.auth.token
            },
            body: {
                id: payload.id
            }
        }).then(response => {
            return response.json();
        }).then(data => {
            if (data.ok) {
                commit('setMessage', { message: data.message, class: 'success'});
            } else {
                commit('setMessage', { message: data.message, class: 'error'});
            }
            router.push('/users');
        }).catch(error => {
            commit('setMessage', { message: error.message, class: 'error'});
            router.push('/users');
        });
    }
}