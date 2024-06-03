import { defineStore } from 'pinia'
import { useApi } from "~/composables/useApi";

export const useEventStore = defineStore('events', () => {
  const list = ref([])

  function getEvents(payload, callback) {
    useApi().get(`/events?page=${payload.page}`)
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

  async function getEvent(id: number, cb) {
    const result = await useApi().get(`/events/${id}`)
    if(result.status == 200) {
      cb(result.data)
    }
  }

  function updateEvent(id: number, event, callback) {
    useApi()
      .patch(`/events/${id}`, event)
      .then(result => {
        callback(result)
      })
      .catch(error => console.log(error));
  }

  function deleteEvent(id, callback) {
    useApi().delete(`/events/${id}`)
      .then(result => { callback(result) })
      .catch(error => console.log(error));
  }

  function createEvent(event, callback) {
    useApi().delete(`/events`, event)
      .then(result => {
        callback(result)
      })
      .catch(error => console.log(error));
  }

  return { list, getEvents, getEvent, updateEvent, createEvent, deleteEvent }
})
