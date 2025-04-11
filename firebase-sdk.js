// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyDbJEB7oDumwswJSHpqb-dtlKUekDmVB7c",
  authDomain: "proyekakhir-7f1e1.firebaseapp.com",
  databaseURL: "https://proyekakhir-7f1e1-default-rtdb.asia-southeast1.firebasedatabase.app",
  projectId: "proyekakhir-7f1e1",
  storageBucket: "proyekakhir-7f1e1.firebasestorage.app",
  messagingSenderId: "1066080402818",
  appId: "1:1066080402818:web:da10d4f0ba82302002e39b",
  measurementId: "G-WD837KN2G0"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);