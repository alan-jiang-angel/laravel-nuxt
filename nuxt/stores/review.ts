import { defineStore } from 'pinia'
import { useApi } from "~/composables/useApi";

export type Review = {
  id: number;
  type: string;
  name: string;
  position: string;
  photo: string;
  content: string;
  link: string;
}

export const useReviewStore = defineStore('reviews', () => {
  const list = ref([])

  function getReviews(payload, callback) {
    useApi().get(`/reviews?page=${payload.page}`)
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

  async function getReview(id: number, cb) {
    const result = await useApi().get(`/reviews/${id}`)
    if(result.status == 200) {
      cb(result.data)
    }
  }

  function updateReview(id: number, review: Review, callback) {
    useApi()
      .patch(`/reviews/${id}`, review)
      .then(result => {
        callback(result)
      })
      .catch(error => console.log(error));
  }

  function createReview(review: Review, callback) {
    useApi().post(`/reviews`, review)
      .then(result => {
        callback(result)
      })
      .catch(error => console.log(error));
  }

  function deleteReview(id, callback) {
    useApi().delete(`/reviews/${id}`)
      .then(result => {
        callback(result)
      })
      .catch(error => console.log(error));
  }

  return { list, getReviews, getReview, updateReview, createReview, deleteReview }
})
