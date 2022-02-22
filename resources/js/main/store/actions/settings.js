export default {

    getSettings(context) {

        return new Promise((resolve) => {
            api
                .get('setting.get')

                .then(response => {
                    
                    context.commit('set', {type: 'setting', data: response.data.setting});

                    resolve()
                })
            ;
        })
    },
}