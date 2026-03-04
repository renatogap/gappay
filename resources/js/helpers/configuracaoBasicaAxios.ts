import axios, { AxiosInstance } from 'axios'

const api: AxiosInstance = axios.create({
  withCredentials: true,
  withXSRFToken: true,
  headers: {
      'X-Requested-With': 'XMLHttpRequest',
  },
})

export default api
