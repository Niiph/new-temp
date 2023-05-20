import axios from 'axios';
import ApiConfig from './ApiConfig';

export const Get = async (url: string) => {
    try {
        const response = await axios.get(url, ApiConfig);
        return response.data;
    } catch (error) {
        console.error('Error fetching data:', error);
        throw error;
    }
};

export const Put = async (url: string, payload: object) => {
    try {
        const response = await axios.put(url, payload, ApiConfig);
        return response.data;
    } catch (error) {
        console.error('Error fetching data:', error);
        throw error;
    }
};

export const Post = async (url: string, payload: object) => {
    try {
        const response = await axios.post(url, payload, ApiConfig);
        return response.data;
    } catch (error) {
        console.error('Error fetching data:', error);
        throw error;
    }
};
