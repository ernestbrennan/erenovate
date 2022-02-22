import axios from 'axios';

const service = axios.create({
  baseURL: '/api',
  // timeout: 10000,
  withCredentials: true
});

service.interceptors.request.use(
  config => {
    return config;
  },
  error => {
    Promise.reject(error);
  }
);

service.interceptors.response.use(
  response => {
    return response
  },
  
  error => {
  
    console.log(error);
    if (error.response && error.response.status === 401) {
      return window.location = error.response.data.redirect_url;
    }
    
    if (error.response && error.response.status === 422){
      return Promise.reject(Object.assign({}, error.response.data))
    }
    
    return Promise.reject(Object.assign({}, error));
  }
);

export default service;
