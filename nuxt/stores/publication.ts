import { defineStore } from 'pinia'
import { useApi } from "~/composables/useApi";

export const usePublicationStore = defineStore('publications', () => {
  const list = ref([])

  function getPublications(payload, callback) {
    useApi().get(`/publications?page=${payload.page}`)
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

  async function getPublication(id: number, cb) {
    const result = await useApi().get(`/publications/${id}`)
    if(result.status == 200) {
      cb(result.data)
    }
  }

  function updatePublication(id: number, publication, callback) {
    useApi()
      .patch(`/publications/${id}`, publication)
      .then(result => {
        callback(result)
      })
      .catch(error => console.log(error));
  }

  function createPublication(publication, callback) {
    useApi().post(`/publications`, publication)
      .then(result => {
        callback(result)
      })
      .catch(error => {
        callback({ data: { ok: false, message: "failed" } })
      });
  }

  function deletePublication(id, callback) {
    useApi().delete(`/publications/${id}`)
      .then(result => { callback(result) })
      .catch(error => console.log(error))
  }

  return { list, getPublications, getPublication, updatePublication, createPublication, deletePublication }
})
