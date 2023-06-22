<template>
  <div class="file-uploader">
    <div class="file-upload-action">
      <div class="row align-items-stretch py-3">
        <div class="col-3 align-items-center justify-content-center">
          <div v-if="!selectedFilePreview" class="d-flex align-items-center justify-content-center preview-area">
            <p class="text-center mb-0 ">Select an image.</p>
          </div>
          <img v-if="selectedFilePreview" :src="selectedFilePreview" alt="" class="img-thumbnail" />
        </div>
        <div class="col">
          <input type="file" ref="fileInput" @change="handleFileChange" accept="image/*" class="form-control" >
        </div>
      </div>
      
      <button :aria-busy="uploading" @click="uploadImage" class="btn btn-primary mb-3" :disabled="!selectedFile">
        <div class="spinner-border spinner-border-sm" role="status" v-if="uploading">
          <span class="visually-hidden">Loading...</span>
        </div>
        Upload
      </button>
    </div>
    <div class="file-previews">
      <p>{{ filePreviewsLabel }}</p>

      <div class="row">
        <div class="col-12 col-md-6 mb-3" v-for="file in uploadedFiles" :key="file.name">
          <file-preview :file="file"></file-preview>
        </div>
      </div>
      
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import FilePreview from './FilePreview.vue';

const uploadedFiles = ref([]);

const selectedFile = ref(null);

const uploading = ref(false);

const fileInput = ref(null);

const filePreviewsLabel = computed(() => {
  if (uploadedFiles.value.length === 0) {
    return 'No photos added.';
  } else if (uploadedFiles.value.length === 1) {
    return '1 photo added!';
  } else {
    return `${uploadedFiles.value.length} photos added!`;
  }
})

const selectedFilePreview = computed(() => {
  if (selectedFile.value) {
    return URL.createObjectURL(selectedFile.value);
  }
  return false
});

const handleFileChange = (event) => {
  selectedFile.value = event.target.files[0];
};
const uploadImage = () => {
  const formData = new FormData();
  formData.append('image', selectedFile.value);

  uploading.value = true;

  axios.post('/api/upload', formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
  .then(response => {
    console.log('Image uploaded successfully');
    // Handle the response as needed
    const file_result = response.data.data;
    file_result.preview = selectedFilePreview.value;
    uploadedFiles.value.push(file_result);
    selectedFile.value = null;
    fileInput.value = null;
    uploading.value = false;
  })
  .catch(error => {
    console.error('Error uploading image:', error);
    // Handle the error as needed
    uploading.value = false;
  });
};
</script>
