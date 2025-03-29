import axios from 'axios';
import { toastSuccess, toastError } from './toasterService';

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
            toastSuccess('Data posted successfully.');
            return response.data;
        } catch (error) {
            toastError('Post request failed.');
            throw error;
        }
    },
    // PUT request
    async put(url: string, data: any, config: any = {}) {
        try {
            const response = await axios.put(url, data, config);
            toastSuccess('Data updated successfully.');
            return response.data;
        } catch (error) {
            toastError('Put request failed.');
            throw error;
        }
    },
    // DELETE request
    async delete(url: string, config: any = {}) {
        try {
            const response = await axios.delete(url, config);
            toastSuccess('Deleted successfully.');
            return response.data;
        } catch (error) {
            toastError('Delete request failed.');
            throw error;
        }
    },
    // ...existing code...
};
