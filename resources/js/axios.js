import axios from 'axios'

const http = axios.create({
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
    },
})

http.interceptors.request.use((config) => {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    if (token) {
        config.headers['X-CSRF-TOKEN'] = token
    }
    return config
})

http.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            window.location.href = '/login'
        }
        return Promise.reject(error)
    }
)

export default http
