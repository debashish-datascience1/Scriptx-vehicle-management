import home from "@/components/pages/Home/Home.vue";
import about from "@/components/pages/About/About.vue";
import contact from "@/components/pages/Contact/Contact.vue";
import BookingHistory from "@/components/pages/BookingHistory/BookingHistory.vue";
import login from "@/components/pages/authentication/login.vue";
import forgetPassword from "@/components/pages/authentication/forgetPassword.vue";
import resetPassword from "@/components/pages/authentication/resetPassword.vue";
import register from "@/components/pages/authentication/register.vue";

export default [
  {
    path: "/",
    component: home,
    name: "home",
  },
  {
    path: "/about",
    component: about,
  },
  {
    path: "/contact",
    component: contact,
  },
  {
    path: "/booking-history",
    component: BookingHistory,
    meta: { requiresAuth: true },
  },
  {
    path: "/login",
    component: login,
    name: "login",
  },
  {
    path: "/forget-password",
    component: forgetPassword,
    name: "forget-password",
  },
  {
    path: "/reset-password/:token?",
    name: "reset-password",
    component: resetPassword,
  },
  {
    path: "/register",
    component: register,
  },
];
