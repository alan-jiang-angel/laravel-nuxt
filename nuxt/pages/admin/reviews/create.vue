<script lang="ts" setup>
import {useReviewStore} from "~/stores/review";

const router = useRouter();

const isEdit = ref(false);
const reviewId = ref(0);
const reviews = useReviewStore();
const toast = useToast()

const types = [ { name: 'Video', value: 'video' }, { name: 'Text', value: 'text' } ]
const content = ref("")
const state = reactive({
  type: 'video',
  name: "",
  position: "",
  photo: "",
  link: "https://"
});

onMounted(() => {
  let { id, edit } = router.currentRoute.value.query;
  isEdit.value = edit || false
  reviewId.value = id || 0
  if(id)
    reviews.getReview(id, (data) => {
      state.name = data.name;
      state.position = data.position;
      state.link = data.review_types == 'video' ? data.review : "https://";
      state.type = data.review_types;
      state.photo = data.photo;
      content.value = data.review_types == 'text' ? data.review : "";
    })
})

const handleSubmit = (e) => {
  if(!isEdit.value) {
    reviews.createReview({
      review_types: state.type,
      name: state.name,
      position: state.position,
      photo: state.photo,
      review: state.type == 'video' ? state.link : content.value,
    }, ({ data }) => {
      if(data.ok == true) {
        state.type = 'video'
        state.name = ""
        state.position = ""
        state.photo = ""
        state.link = "https://"
        content.value = ""
        toast.add({ title: 'Create customer review success!', color: 'primary' })
      } else {
        toast.add({ title: 'Create customer review failed!', description: data.message, color: 'red' })
      }
    })
  } else {
    reviews.updateReview(
      reviewId.value,
      {
        review_types: state.type,
        name: state.name,
        position: state.position,
        photo: state.photo,
        review: state.type == 'video' ? state.link : content.value,
      },
      (data) => console.log(data)
    )
    toast.add({ title: 'Hello world!' })
  }
};

const handleBack = (e) => {
  router.back();
};


</script>
<template>
  <UBreadcrumb
    divider="/"
    :links="[{ label: 'Home', to: '/' }, { label: 'Customer Reviews', to: '/admin/reviews'  }, { label: isEdit ? 'Edit' : 'Create' } ]"
    class="mb-5"
  />

  <UDivider class="mb-7" />

  <UCard :ui="{ body: { base: 'grid grid-cols-1' } }">
    <UForm :state="state" class="space-y-4">
      <UFormGroup label="Type" name="type" eager-validation>
        <USelect v-model="state.type" :options="types" name="type" option-attribute="name"/>
      </UFormGroup>

      <UFormGroup label="Name" name="name" eager-validation required>
        <UInput v-model="state.name" placeholder="" />
      </UFormGroup>

      <UFormGroup label="Position, company" name="position" eager-validation required>
        <UInput v-model="state.position" placeholder="" />
      </UFormGroup>

      <UFormGroup label="Photo" name="photo" eager-validation required>
        <UInput v-model="state.photo" placeholder="" />
      </UFormGroup>

      <UFormGroup v-if="state.type === 'text'" label="Content" name="content" eager-validation>
        <UTextarea v-model="content" :rows="3" />
      </UFormGroup>

      <UFormGroup v-if="state.type == 'video'" label="Link" name="link" eager-validation>
        <UInput v-model="state.link" placeholder="" />
      </UFormGroup>

      <UButton
        type="submit"
        color="primary"
        class="mr-3"
        @click="handleSubmit"
        :label="!isEdit ? 'Submit' : 'Update'"
      />
      <UButton type="button" variant="outline" @click="handleBack" label="Back" />
    </UForm>
  </UCard>
</template>
