import axios from 'axios'

export const useApi = () => {
  const baseURL = 'http://localhost:8000/api/v1'
  const storeUser = useAuthStore()

  return axios.create({
    baseURL,
    headers: {
      Authorization: `Bearer ${storeUser.token}`
    }
  })
}

export const useTApi = () => {
  const baseURL = 'http://localhost:8000/api/v1'

  return axios.create({
    baseURL
  })
}
