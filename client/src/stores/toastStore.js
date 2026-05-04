import { defineStore } from 'pinia';

export const useToastStore = defineStore('toast', {
    state: () => ({
        messages: [], 
        type: null
    }),
    actions: {
        show( type = 'Success') {
            
            this.type = type;
            setTimeout(() => {
              
                this.messages = [];
                this.type = null;
            }, 3000);
        },
        close(){
            this.messages = [];
        }
    }
});