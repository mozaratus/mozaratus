import { writable } from 'svelte/store';


let data = [];

const apiURL = "https://jsonplaceholder.typicode.com/todos";

async function getData(){
    const response = await fetch(apiURL);
    data = (await response.json()).slice(0,20);
	  console.log('Response:', data);
}
getData();

export const testsStore = writable(data);
