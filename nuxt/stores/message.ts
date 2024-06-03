import { defineStore } from 'pinia'
import { useApi } from "~/composables/useApi";

export const useMessageStore = defineStore('messages', () => {
  const list = ref([])

  function getMessages(payload, callback) {
    useApi().get(`/messages?page=${payload.page}`)
      .then((result) => {
        if(result.status == 200) {
          list.value = result.data.data
          callback(true, result.data.total)
        }
      })
      .catch(error => {
        console.log(error)
        callback(false)
      })
  }

  function deleteMessages(payload, callback) {
    useApi().post(`/messages/items`, payload)
      .then(result => {
        callback(true, result)
      })
      .catch(error => callback(false))
  }

  return { list, getMessages, deleteMessages }
})
