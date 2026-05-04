import { defineStore } from "pinia";

export const useSearchStore = defineStore("search", {

  state: () => ({
    searchWord: '',
  }),

  getters: {
    searchword() {
      return this.searchWord.toLowerCase();
    },
  },

  actions: {
    resetSearchWord(){
        this.searchWord = '';
    },
    setSearchWord(value){
        this.searchWord = value.trim();
    }
  },
});
