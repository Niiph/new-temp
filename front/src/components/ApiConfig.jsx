const ApiConfig = {
    headers: {
        Authorization: 'Bearer ' + localStorage.getItem('jwt_token')
    }
};

export default ApiConfig;
