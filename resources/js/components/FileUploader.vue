<template>
  <div>
    <input type="file" ref="fileInput" @change="handleFileChange" accept="image/*">
    <button @click="uploadImage">Upload</button>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const selectedFile = ref(null);
const handleFileChange = (event) => {
  selectedFile.value = event.target.files[0];
};
const uploadImage = () => {
  const formData = new FormData();
  formData.append('image', selectedFile.value);

  axios.post('/api/upload', formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
  .then(response => {
    console.log('Image uploaded successfully');
    // Handle the response as needed
  })
  .catch(error => {
    console.error('Error uploading image:', error);
    // Handle the error as needed
  });
};
</script>
