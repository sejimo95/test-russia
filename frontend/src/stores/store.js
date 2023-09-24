import { defineStore } from 'pinia';

export const useStore = defineStore('store', {
  state: () => ({
    api: process.env.API,
    connectionError: 'Connection Failed!',
    user: [],
  }),
  getters: {
  },
  actions: {
  },
});
