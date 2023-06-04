import axios from 'axios';
// import ApiConfig from './ApiConfig';
import Cookies from 'js-cookie';
import Links from 'routes/ApiRoutes';

axios.defaults.withCredentials = true;
const refreshAuthLogic = (failedRequest) =>
    axios.post(Links('tokenRefresh')).then((tokenRefreshResponse) => {
        const { token } = tokenRefreshResponse.data;
        Cookies.set('token', token, { secure: true, sameSite: 'strict' });
        // failedRequest.response.config.headers['Authorization'] = 'Bearer ' + tokenRefreshResponse.data.jwt;
        return Promise.resolve().catch((error) => {
            console.error('Error refreshing token: ', error);
        });
    });

axios.interceptors.response.use(
    (response) => response,
    (error) => {
        const originalRequest = error.config;
        if (error.response && error.response.status === 401 && !originalRequest._retry) {
            Cookies.remove('token');
            originalRequest._retry = true;
            return refreshAuthLogic(originalRequest).then(() => {
                return axios(originalRequest);
            });
        }
        return Promise.reject(error);
    }
);

export const Get = async (url: string) => {
    try {
        const response = await axios.get(url);
        return response.data;
    } catch (error) {
        console.error('Error fetching data:', error);
        throw error;
    }
};

export const Put = async (url: string, payload: object) => {
    try {
        const response = await axios.put(url, payload);
        return response.data;
    } catch (error) {
        console.error('Error fetching data:', error);
        throw error;
    }
};

export const Post = async (url: string, payload: object) => {
    try {
        const response = await axios.post(url, payload);
        return response.data;
    } catch (error) {
        console.error('Error fetching data:', error);
        throw error;
    }
};
