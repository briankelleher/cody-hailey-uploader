<template>
  <div class="file-uploader">
    <div class="file-upload-action">
      <div class="row align-items-stretch py-3">
        <div class="col-12 col-sm-3 mb-2 mb-sm-0 d-flex align-items-center justify-content-center">
          <div v-if="!selectedFilePreview" class="d-flex align-items-center justify-content-center preview-area">
            <p class="text-center mb-0 me-2">Select an image</p>
            <svg xmlns="http://www.w3.org/2000/svg" style="height: 1em;" width="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
            </svg>
          </div>
          <img v-if="selectedFilePreview" :src="selectedFilePreview" alt="" class="img-thumbnail cap-height-300" />
        </div>
        <div class="col">
          <input type="file" ref="fileInput" @change="handleFileChange" accept="image/*" class="form-control mb-1" >
          <p v-if="selectedFile">File size: {{ selectedFileSize }}</p>
          <div v-if="progress > 0" class="progress" role="progressbar" aria-label="Example with label" :aria-valuenow="progressRounded" aria-valuemin="0" aria-valuemax="100">
            <div class="progress-bar" :style="`width: ${progressDisplay}`">{{ progressDisplay }}</div>
          </div>
          <p v-if="progress === 100">Finalizing upload...</p>
          <div v-if="errorText" class="alert alert-danger" role="alert">{{errorText}}</div>
        </div>
      </div>
      
      <button :aria-busy="uploading" @click="uploadImage" class="btn btn-primary mb-3" :disabled="!selectedFile">
        <div class="spinner-border spinner-border-sm" role="status" v-if="uploading">
          <span class="visually-hidden">Loading...</span>
        </div>
        Upload
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import FilePreview from './FilePreview.vue';
import { store } from '../store.js';

const selectedFile = ref(null);

const uploading = ref(false);

const fileInput = ref(null);

const progress = ref(0);

const errorText = ref(null);

const selectedFilePreview = computed(() => {
  if (selectedFile.value) {
    return URL.createObjectURL(selectedFile.value);
  }
  return false
});

const selectedFileSize = computed(() => {
  if ( !selectedFile.value) {
    return "N/A";
  }
  let units = ["bytes", "KB", "MB", "GB", "TB"];
  let i = 0;
  let fileSize = selectedFile.value.size;
  
  while (fileSize >= 1024 && i < units.length - 1) {
    fileSize /= 1000;
    i++;
  }
  
  return fileSize.toFixed(1) + " " + units[i];
})

const progressRounded = computed(() => {
    return progress.value.toFixed(1)
})
const progressDisplay = computed(() => {
  return progressRounded.value + "%";
})

const handleFileChange = (event) => {
  selectedFile.value = event.target.files[0];
  errorText.value = null;
};
const uploadImage = () => {
  const formData = new FormData();
  formData.append('image', selectedFile.value);

  uploading.value = true;
  errorText.value = null;

  axios.post('/api/upload', formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    },
    onUploadProgress: (progressEvent) => {
      console.log(progressEvent)
      console.log(progressEvent.loaded / progressEvent.total)
      progress.value = (progressEvent.loaded / progressEvent.total) * 100;
    }
  })
  .then(response => {
    console.log('Image uploaded successfully');
    // Handle the response as needed
    const file_result = response.data.data;
    file_result.preview = selectedFilePreview.value;
    store.addFile(file_result);
    selectedFile.value = null;
    fileInput.value.value = null;
    uploading.value = false;
    progress.value = 0;
  })
  .catch(error => {
    console.error('Error uploading image:', error);
    console.log({...error})
    // Handle the error as needed
    uploading.value = false;
    progress.value = 0;
    if ( error.response?.status === 413 ) {
      errorText.value = 'The file is too large. Please select a smaller file, or email your photo!';
    } else {
      errorText.value = `There was an error uploading the file. Please try again, or email your photos!`;
    }
  });
};
</script>
