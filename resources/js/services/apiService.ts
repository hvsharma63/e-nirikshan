import axios from 'axios';
import { toastError } from './toasterService';

export default {
    // GET request
    async get(url: string, config: any = {}) {
        try {
            const response = await axios.get(url, config);
            return response.data;
        } catch (error) {
            throw error;
        }
    },
    // POST request
    async post(url: string, data: any, config: any = {}) {
        try {
            const response = await axios.post(url, data, config);
            return response.data;
        } catch (error) {
            toastError('Something Went Wrong!');
            throw error;
        }
    },
    // PUT request
    async put(url: string, data: any, config: any = {}) {
        try {
            const response = await axios.put(url, data, config);
            return response.data;
        } catch (error) {
            toastError('Something Went Wrong!');
            throw error;
        }
    },
    // DELETE request
    async delete(url: string, config: any = {}) {
        try {
            const response = await axios.delete(url, config);
            return response.data;
        } catch (error) {
            toastError('Something Went Wrong!');
            throw error;
        }
    },
    // ...existing code...
};
