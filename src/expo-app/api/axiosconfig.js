import axios from 'axios';

const api = axios.create({
    // Use your computer's IP address (e.g., 192.168.x.x) if using a physical device
    baseURL: 'http://localhost/item-rental-api/', 
    timeout: 1000,
    headers: { 'Content-Type': 'application/json' }
});

export default api;