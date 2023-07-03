import { reactive } from 'vue'

export const store = reactive({
  uploadedFiles: [],
  addFile(file) {
    this.uploadedFiles.push(file)
  }
})